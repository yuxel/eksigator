-- user table
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(50) NOT NULL,
  `apiKey` varchar(50) UNIQUE NOT NULL,
  PRIMARY KEY  (id)
);

-- entries table
CREATE TABLE `entries` (
  `userId` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(60),
  `lastRead` int(11) DEFAULT 0,
  `status` tinyint(2),
  `lastId` int(11) DEFAULT 0,
   FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
   INDEX userId (userId)
);

alter table users add column auth tinyint(2) default 0;
alter table users add column active tinyint(2) default 0;
alter table users add column hash varchar(21) default 0;
alter table entries add column added int(11) after lastRead;
alter table entries add column deleted int(11) default '0';
alter table entries add KEY deleted(deleted);
ALTER TABLE entries MODIFY title VARCHAR( 120 ) ;


-- facebook table
CREATE TABLE `facebook` (
  `fb_id` int(10) unsigned NOT NULL DEFAULT 0,
  `eksigator_id` int(10) unsigned NOT NULL DEFAULT 0,
  `interval` tinyint DEFAULT 0,
   UNIQUE fb_id (fb_id)
);

