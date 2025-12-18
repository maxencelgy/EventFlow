// ***********************************************************
// Support file for Cypress E2E tests
// ***********************************************************

// Custom command to login
Cypress.Commands.add('login', (email, password) => {
  cy.visit('/login')
  cy.get('input[name="email"]').type(email)
  cy.get('input[name="password"]').type(password)
  cy.get('button[type="submit"]').click()
  cy.url().should('include', '/dashboard')
})

// Custom command to logout
Cypress.Commands.add('logout', () => {
  cy.get('form[action*="logout"]').submit()
})
