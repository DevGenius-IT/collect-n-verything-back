import vine from '@vinejs/vine'

export const signUpValidator = vine.compile(
  vine.object({
    username: vine.string(),
    password: vine.string().alphaNumeric(),
    password_confirmation: vine.string().sameAs('password'),
    profile: vine.object({
      firstname: vine.string(),
      lastname: vine.string(),
      email: vine.string().email(),
      phoneNumber: vine.string().mobile(),
    }),
  })
)

export const signInValidator = vine.compile(
  vine.object({
    username: vine.string(),
    password: vine.string().alphaNumeric(),
  })
)

