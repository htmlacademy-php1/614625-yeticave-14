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
  simbolCode varchar(64) NOT NULL UNIQUE
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
   dataCreate date NOT NULL,
   description varchar (255),
   img varchar (64),
   beginPrice int NOT NULL,
   dateCompletion date,
   bidStep int NOT NULL,
   userAutor int NOT NULL REFERENCES users (id) ,
   userWinner int NOT NULL REFERENCES users(id) ,
   categoryId int NOT NULL REFERENCES categories(id)
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
   id int(11) PRIMARY KEY AUTO_INCREMENT,
   date date NOT NULL,
   price int(11) NOT NULL,
   userId int REFERENCES users (id),
   lotId int REFERENCES lots (id)
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
  dateRegistration date NOT NULL,
  email varchar(64) NOT NULL UNIQUE,
  password varchar(64) NOT NULL UNIQUE,
  contact varchar(122) NOT NULL,
  idlot int REFERENCES lots (id),
  userBetStep int REFERENCES lots (bidStep)
);


/*Запросы к БД из задания 10*/

/*существующий список категорий*/
INSERT INTO categories (name,simbolCode)
VALUES ('Доски и лыжи','boards-and-skis'),
('Крепления','fasteners'),
('Ботинки','boots'),
('Одежда','clothes'),
('Инструменты','tools'),
('Разное','any')

/*придумайте пару пользователей*/
INSERT INTO users (email,dateRegistration,PASSWORD,contact)
VALUES ('user1@mail.ru','23-04-04','test','89123123'),
('user2@mail.ru','23-04-04','test2','891231232')


