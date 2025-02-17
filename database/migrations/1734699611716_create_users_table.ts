import { BaseSchema } from '@adonisjs/lucid/schema'

export default class extends BaseSchema {
  protected tableName = 'user_us'

  async up() {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('us_id').primary().notNullable()
      table.string('us_username', 50).notNullable().unique()
      table.string('us_password').notNullable()
      table.string('us_firstname').notNullable()
      table.string('us_lastname').notNullable()
      table.string('us_phone_number').notNullable()
      table.string('us_email').notNullable().unique()
      table.string('us_strip_id').notNullable().unique()
      table.timestamp('us_created_at').notNullable()
      table.timestamp('us_updated_at').nullable()
      table.timestamp('us_deleted_at').nullable()
    })
  }

  async down() {
    this.schema.dropTable(this.tableName)
  }
}
