/**
 * Test E2E #1: Authentification
 * Vérifie la connexion et déconnexion des utilisateurs
 */
describe('Authentification', () => {
  beforeEach(() => {
    cy.visit('/login')
  })

  it('devrait afficher la page de connexion', () => {
    cy.get('h1').should('exist')
    cy.get('input[name="email"]').should('be.visible')
    cy.get('input[name="password"]').should('be.visible')
    cy.get('button[type="submit"]').should('be.visible')
  })

  it('devrait afficher une erreur avec des identifiants invalides', () => {
    cy.get('input[name="email"]').type('wrong@email.com')
    cy.get('input[name="password"]').type('wrongpassword')
    cy.get('button[type="submit"]').click()
    
    cy.get('.text-red-600, .bg-red-50').should('be.visible')
  })

  it('devrait connecter un admin avec des identifiants valides', () => {
    cy.fixture('users').then((users) => {
      cy.get('input[name="email"]').type(users.admin.email)
      cy.get('input[name="password"]').type(users.admin.password)
      cy.get('button[type="submit"]').click()
      
      cy.url().should('include', '/dashboard')
    })
  })

  it('devrait permettre la déconnexion', () => {
    cy.fixture('users').then((users) => {
      cy.login(users.admin.email, users.admin.password)
      
      // Chercher le formulaire de déconnexion et le soumettre
      cy.get('form[action*="logout"] button[type="submit"]').click()
      cy.url().should('include', '/login')
    })
  })
})
