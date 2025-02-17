import { test } from '@japa/runner'
import sinon from 'sinon'
import { signInValidator, signUpValidator } from '#validators/auth'
import AuthController from '#controllers/http/AuthController'
import User from '#models/user'
import { HttpContext } from '@adonisjs/core/http'
import { AccessToken } from '@adonisjs/auth/access_tokens'
import { Secret } from '@adonisjs/core/helpers'

test.group('AuthController', (group) => {
  let authController: AuthController
  let mockAuth: HttpContext['auth']  // Utilisation de HttpContext pour l'auth
  let mockRequest: any
  let mockResponse: any
  let mockHttpContext: HttpContext

  group.setup(() => {
    authController = new AuthController()

    // Mock de l'objet auth pour les tests de signout
    mockAuth = {
      user: { id: 1 }, // Mock de l'utilisateur dans le contexte d'authentification pour signout
    } as any

    // Mock de la requête et de la réponse
    mockRequest = {
      validateUsing: sinon.stub(),
    }

    mockResponse = {
      ok: sinon.stub(),
      created: sinon.stub(),
    }

    // Simulation du HttpContext complet avec la méthode inspect
    mockHttpContext = {
      request: mockRequest,
      response: mockResponse,
      auth: mockAuth,
      logger: { info: sinon.stub() } as any,  // Mock du logger
      containerResolver: {} as any,  // Mock du containerResolver
      params: {},  // Mock des paramètres
      subdomains: [],  // Mock des sous-domaines
      inspect: sinon.stub().returns('mocked context inspect'), // Ajout de la méthode inspect simulée
      ...{}  // Vous pouvez ajouter d'autres propriétés nécessaires ici
    } as HttpContext
  })

  group.teardown(() => {
    sinon.restore()
  })

  test('signin: should return a token and user if credentials are valid', async ({ assert }) => {
    // Mock de la requête et de la réponse
    mockRequest.validateUsing.resolves({ username: 'testuser', password: 'password' })
    mockResponse.ok = sinon.stub()

    // Stub de User.verifyCredentials et accessTokens.create
    sinon.stub(User, 'verifyCredentials').resolves(user)
    sinon.stub(User.accessTokens, 'create').resolves(mockToken)

    // Appel de la méthode du contrôleur
    await authController.signin(mockHttpContext)

     // Stub de User.verifyCredentials et accessTokens.create
     const verifyCredentialsStub = sinon.stub(User, 'verifyCredentials').resolves(user) 

    // Assertions
    assert.isTrue(mockRequest.validateUsing.calledWithExactly(signInValidator))
    assert.isTrue(verifyCredentialsStub.calledWithExactly('testuser', 'password'))
    assert.isTrue(mockResponse.ok.calledWithExactly({ token: 'test-token', user }))
  })

  // test('signup: should create a user and return a token', async ({ assert }) => {
  //   const requestData = { username: 'newuser', password: 'password' }

  //   mockRequest.validateUsing.resolves(requestData)

  //   // Stub de User.create et accessTokens.create
  //   sinon.stub(User, 'create').resolves(user)
  //   sinon.stub(User.accessTokens, 'create').resolves(mockToken)

  //   // Appel de la méthode du contrôleur
  //   await authController.signup(mockHttpContext)

  //   const verifyCredentialsStub = sinon.stub(User, 'verifyCredentials').resolves(user)

  //   // Assertions
  //   assert.isTrue(mockRequest.validateUsing.calledWithExactly(signUpValidator))
  //   assert.isTrue(verifyCredentialsStub.calledWithExactly(requestData.username, requestData.password))
  //   assert.isTrue(mockResponse.created.calledWithExactly({ token: 'test-token', user }))
  // })

  // test('signout: should delete user token and return a success message', async ({ assert }) => {
  //   const user = { id: 1 }

  //   // Stub de User.findOrFail et accessTokens.delete
  //   sinon.stub(User, 'findOrFail').resolves(user)
  //   sinon.stub(User.accessTokens, 'delete').resolves()

  //   // Appel de la méthode du contrôleur
  //   await authController.signout(mockHttpContext)

  //   // Assertions
  //   assert.isTrue(User.findOrFail.calledWithExactly(1))
  //   assert.isTrue(User.accessTokens.delete.calledWithExactly(user, 1))
  //   assert.isTrue(
  //     mockResponse.ok.calledWithExactly({
  //       success: true,
  //       message: 'User logged out',
  //       data: 1,
  //     })
  //   )
  // })
})


// Exemple de simulation complète avec des fonctions pour `allows` et `denies`
const mockToken: AccessToken = {
  identifier: 'mock-identifier',
  type: 'Bearer',
  name: null,
  abilities: [],
  lastUsedAt: null,
  expiresAt: null,
  tokenableId: 1,
  hash: 'mock-hash',
  createdAt: new Date(),
  updatedAt: new Date(),

  // Fonction pour `denies`
  denies: (ability: string) => {
    // Simulez la logique de refus ici, par exemple :
    return ability === 'admin' // Refuse l'ability 'admin'
  },

  // Fonction pour `allows`
  allows: (ability: string) => {
    // Simulez la logique d'autorisation ici, par exemple :
    return ability === 'read' // Autorise l'ability 'read'
  },

  // Stub pour la méthode `authorize`
  authorize: sinon.stub().resolves(true),

  // Stub pour la méthode `isExpired`
  isExpired: sinon.stub().returns(false),
  verify: sinon.stub().returns(true), // Mock the verify method
  toJSON: sinon.stub().returns({
    token: 'mock-token', // Mock the JSON structure returned by toJSON
  }),
}

const user = {
  id: 1,
  username: 'testuser',
  password: 'password',
  firstname: 'Test',
  lastname: 'User',
  phoneNumber: '1234567890',
  email: 'testuser@example.com', // Ajoutez l'email
  createdAt: new Date(),
  updatedAt: new Date(),
  deletedAt: null, // Ajoutez deletedAt
  stripId: true, 
}