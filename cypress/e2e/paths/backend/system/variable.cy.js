const route = cy.testRoutes.backend.system.variables;

describe(`Checks admin system "${route.h3}"`, () => {
    beforeEach('Log in the user', () => {
        cy.adminLogInValidUser();
        cy.adminGoToTestRoute(route);
    });

    it(`tests classes and title`, () => {
        cy.adminTestRoute(route);
    });
});