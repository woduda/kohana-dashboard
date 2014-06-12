SET storage_engine=InnoDB;

DROP TABLE IF EXISTS `roles_users`;
DROP TABLE IF EXISTS `user_tokens`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
       `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT
     , `email` VARCHAR(254) NOT NULL
     , `username` VARCHAR(32) NOT NULL
     , `password` VARCHAR(64) NOT NULL
     , `logins` INTEGER UNSIGNED NOT NULL DEFAULT 0
     , `last_login` INTEGER UNSIGNED NOT NULL
     , `first_name` VARCHAR(254)
     , `last_name` VARCHAR(254)
     , PRIMARY KEY (`id`)
);

CREATE TABLE `roles` (
       `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT
     , `name` VARCHAR(32) NOT NULL
     , `description` VARCHAR(255) NOT NULL
     , PRIMARY KEY (`id`)
);

CREATE TABLE `user_tokens` (
       `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT
     , `user_id` INTEGER UNSIGNED NOT NULL
     , `user_agent` VARCHAR(40) NOT NULL
     , `token` VARCHAR(40) NOT NULL
     , `created` INTEGER UNSIGNED NOT NULL
     , `expires` INTEGER UNSIGNED NOT NULL
     , PRIMARY KEY (`id`)
);

CREATE TABLE `roles_users` (
       `user_id` INTEGER UNSIGNED NOT NULL
     , `role_id` INTEGER UNSIGNED NOT NULL
     , PRIMARY KEY (`user_id`, `role_id`)
);

ALTER TABLE `users`
  ADD CONSTRAINT `users_email_uq`
      UNIQUE (`email`);

ALTER TABLE `users`
  ADD CONSTRAINT `users_username_uq`
      UNIQUE (`username`);

ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_user_id_fk`
      FOREIGN KEY (`user_id`)
      REFERENCES `users` (`id`)
   ON DELETE CASCADE
   ON UPDATE CASCADE;

ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_user_id_fk`
      FOREIGN KEY (`user_id`)
      REFERENCES `users` (`id`)
   ON DELETE CASCADE
   ON UPDATE CASCADE;

ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_role_id_fk`
      FOREIGN KEY (`role_id`)
      REFERENCES `roles` (`id`)
   ON DELETE CASCADE
   ON UPDATE CASCADE;

INSERT INTO `roles` (`id`, `name`, `description`) VALUES(1, 'login', 'Login privileges, granted after account confirmation');
INSERT INTO `roles` (`id`, `name`, `description`) VALUES(2, 'admin', 'Administrative user, has access to everything.');
