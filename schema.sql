CREATE DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

/*
сущности таблицы yeticave
Категория, Лот, Ставка, Пользователь.
*/

/*
категории
  Поля:
    название;
    символьный код.
*/
CREATE TABLE categories  (
  id int PRIMARY KEY AUTO_INCREMENT,
  name varchar(64) NOT NULL,
  code varchar(64) NOT NULL UNIQUE
);

/*
лот
  Поля
    дата создания: дата и время, когда этот лот был создан пользователем;
    название: задается пользователем;
    описание: задается пользователем;
    изображение: ссылка на файл изображения;
    начальная цена;
    дата завершения;
    шаг ставки.
  Связи:
    автор: пользователь, создавший лот;
    победитель: пользователь, выигравший лот;
    категория: категория объявления.
*/
CREATE TABLE lots (
   id int PRIMARY KEY AUTO_INCREMENT,
   name varchar (122) NOT NULL,
   creation_time datetime NOT NULL,
   description varchar (255),
   img varchar (255),
   begin_price int NOT NULL,
   date_completion date,
   bid_step int NOT NULL,
   user_id int NOT NULL REFERENCES users (id) ,
   winner_id int REFERENCES users(id) ,
   category_id int NOT NULL REFERENCES categories(id)
);

/*
Ставка
  Поля
    дата: дата и время размещения ставки;
    сумма: цена, по которой пользователь готов приобрести лот.
  Связи:
    пользователь;
    лот.
*/
CREATE TABLE bets (
   id int PRIMARY KEY AUTO_INCREMENT,
   creation_time datetime NOT NULL,
   price int NOT NULL,
   user_id int REFERENCES users (id),
   lot_id int REFERENCES lots (id)
);

/*
пользователи
  Поля:
    дата регистрации: дата и время, когда этот пользователь завёл аккаунт;
    email;
    имя;
    пароль: хэшированный пароль пользователя;
    контакты: контактная информация для связи с пользователем.
  Связи:
    созданные лоты ;
    ставки.
*/
CREATE TABLE users (
  id int PRIMARY KEY AUTO_INCREMENT,
  creation_time datetime NOT NULL,
  name varchar(122) NOT NULL,
  email varchar(64) NOT NULL UNIQUE,
  password varchar(64) NOT NULL,
  contact varchar(122) NOT NULL
);


