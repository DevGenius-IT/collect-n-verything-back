import { HttpContext } from '@adonisjs/core/http'
import User from '#models/user'
import { signInValidator, signUpValidator } from '#validators/auth'

export default class AuthController {
  async signin({ request, response }: HttpContext) {
    const data = await request.validateUsing(signInValidator)

    const user = await User.verifyCredentials(data.username, data.password)

    const token = await User.accessTokens.create(user)

    return response.ok({ token: token.toJSON().token, user })
  }

  async signup({ request, response }: HttpContext) {
    const data = await request.validateUsing(signUpValidator)

    const user = await User.create(data)

    const token = await User.accessTokens.create(user)

    return response.created({ token: token.toJSON().token, user })
  }

  async signout({ auth, response }: HttpContext) {
    const getUser = auth.user?.id
    const user = await User.findOrFail(getUser)
    await User.accessTokens.delete(user, user.id)

    return response.ok({
      success: true,
      message: 'User logged out',
      data: getUser,
    })
  }
}
