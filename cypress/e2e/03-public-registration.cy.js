/**
 * Test E2E #3: Inscription publique (Magic Link)
 * Vérifie le formulaire d'inscription sans authentification
 */
describe('Inscription publique', () => {
  let registrationToken

  before(() => {
    // Se connecter en admin pour récupérer un token d'inscription
    cy.fixture('users').then((users) => {
      cy.login(users.admin.email, users.admin.password)
      cy.visit('/events/1')
      
      // Récupérer le token depuis l'input magic-link
      cy.get('#magic-link').invoke('val').then((url) => {
        const parts = url.split('/inscription/')
        if (parts[1]) {
          registrationToken = parts[1]
        }
      })
    })
  })

  it('devrait afficher le formulaire d\'inscription public', () => {
    cy.visit(`/inscription/${registrationToken}`)
    
    cy.get('h1').should('exist')
    cy.get('input[name="first_name"]').should('be.visible')
    cy.get('input[name="last_name"]').should('be.visible')
    cy.get('input[name="email"]').should('be.visible')
    cy.get('button[type="submit"]').should('be.visible')
  })

  it('devrait valider les champs obligatoires', () => {
    cy.visit(`/inscription/${registrationToken}`)
    
    // Soumettre sans remplir les champs
    cy.get('button[type="submit"]').click()
    
    // Les champs required devraient empêcher la soumission
    cy.get('input[name="first_name"]:invalid').should('exist')
  })

  it('devrait inscrire un nouveau participant', () => {
    cy.visit(`/inscription/${registrationToken}`)
    
    const timestamp = Date.now()
    
    cy.get('input[name="first_name"]').type('Test')
    cy.get('input[name="last_name"]').type('Cypress')
    cy.get('input[name="email"]').type(`test.cypress.${timestamp}@example.com`)
    cy.get('input[name="phone"]').type('0612345678')
    cy.get('input[name="organization"]').type('Cypress Testing Inc.')
    cy.get('input[name="function"]').type('QA Engineer')
    
    cy.get('button[type="submit"]').click()
    
    // Vérifier la redirection vers la confirmation
    cy.url().should('include', '/confirmation')
  })

  it('devrait afficher une erreur pour token invalide', () => {
    cy.request({
      url: '/inscription/invalid-token-12345',
      failOnStatusCode: false
    }).then((response) => {
      expect(response.status).to.be.oneOf([404, 500])
    })
  })
})
