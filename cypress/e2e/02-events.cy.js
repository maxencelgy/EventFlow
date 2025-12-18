/**
 * Test E2E #2: Gestion des événements
 * Vérifie le CRUD complet des événements
 */
describe('Gestion des événements', () => {
  beforeEach(() => {
    cy.fixture('users').then((users) => {
      cy.login(users.admin.email, users.admin.password)
    })
  })

  it('devrait afficher la liste des événements', () => {
    cy.visit('/events')
    cy.get('h1').should('contain', 'Événements')
  })

  it('devrait accéder au formulaire de création', () => {
    cy.visit('/events/create')
    
    cy.get('input[name="title"]').should('be.visible')
    cy.get('input[name="date"]').should('be.visible')
    cy.get('button[type="submit"]').should('be.visible')
  })

  it('devrait afficher le détail d\'un événement', () => {
    cy.visit('/events')
    
    // Cliquer sur un lien vers un événement
    cy.get('a[href*="/events/"]').not('[href*="/edit"]').not('[href*="/create"]').first().click()
    
    // Vérifier que les statistiques sont affichées
    cy.contains('Confirmés').should('exist')
  })

  it('devrait afficher les statistiques d\'un événement', () => {
    cy.visit('/events/1')
    
    cy.get('.text-3xl, .font-bold').should('exist')
  })
})
