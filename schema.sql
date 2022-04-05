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
   userAutor int REFERENCES users (id) ,
   userWinner int REFERENCES users(id) ,
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
('Разное','any');

/*придумайте пару пользователей*/
INSERT INTO users (email,dateRegistration,PASSWORD,contact)
VALUES ('user1@mail.ru','23-04-04','test','89123123'),
('user2@mail.ru','23-04-04','test2','891231232');

/*существующий список объявлений;*/
INSERT INTO lots (name,description,beginPrice,img,categoryId,datacreate,dateCompletion,bidStep)
VALUES
       ('2014 Rossignol District Snowboard','описание',10999,'img/lot-1.jpg',1,'22-03-30','22-03-31',200),
       ('DC Ply Mens 2016/2017 Snowboard','описание',159999,'img/lot-2.jpg',1,'22-03-30','22-03-31',200),
       ('Крепления Union Contact Pro 2015 года размер L/XL','описание',8000,'img/lot-3.jpg',2,'22-03-30','22-03-31',200),
       ('Ботинки для сноуборда DC Mutiny Charocal','описание',10999,'img/lot-4.jpg',3,'22-03-30','22-03-31',200),
       ('Куртка для сноуборда DC Mutiny Charocal','описание',7500,'img/lot-5.jpg',4,'22-03-30','22-03-31',200),
       ('Маска Oakley Canopy','описание',5400,'img/lot-6.jpg',6,'22-03-30','22-03-31',200);

/*добавьте пару ставок для любого объявления*/
INSERT INTO bets (lotId,price,userId,date) VALUES
 (1,11199,1,'05-05-22'),
 (1,11399,1,'05-05-22')

/*получить все категории;*/
SELECT * FROM categories;

/*получить самые новые, открытые лоты.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;*/
SELECT
  lots.name,
  lots.beginPrice,
  lots.img,
  categories.name as category,
  bets.price
FROM lots
       left join categories on lots.categoryId = categories.id
       left join bets on lots.id = bets.lotid
order by dataCreate;

/*показать лот по его ID. Получите также название категории, к которой принадлежит лот;*/
SELECT * FROM `lots`
  left join categories on lots.categoryId = categories.id
WHERE lots.id=1;

/*обновить название лота по его идентификатору;*/
UPDATE lots SET name = '2015 Rossignol District Snowboard' WHERE id=1

/*получить список ставок для лота по его идентификатору с сортировкой по дате.*/
SELECT * FROM `bets` WHERE lotId=1 ORDER BY date;
