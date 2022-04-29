use yeticave;
/*Запросы к БД из задания 10*/

/*существующий список категорий*/
INSERT INTO categories (name,code)
VALUES ('Доски и лыжи','boards-and-skis'),
       ('Крепления','fasteners'),
       ('Ботинки','boots'),
       ('Одежда','clothes'),
       ('Инструменты','tools'),
       ('Разное','any');

/*придумайте пару пользователей*/
INSERT INTO users (email, name,creation_time,password,contact)
VALUES ('user1@mail.ru', 'артем','23-04-04','test','89123123'),
       ('user2@mail.ru', 'антон','23-04-04','test2','891231232');

/*существующий список объявлений;*/
INSERT INTO lots (name,user_id,description,begin_price,img,category_id,creation_time,date_completion,bid_step)
VALUES
  ('2014 Rossignol District Snowboard',1,'описание',10999,'img/lot-1.jpg',1,'22-03-30','22-03-31',200),
  ('DC Ply Mens 2016/2017 Snowboard',1,'описание',159999,'img/lot-2.jpg',1,'22-03-30','22-03-31',200),
  ('Крепления Union Contact Pro 2015 года размер L/XL',1,'описание',8000,'img/lot-3.jpg',2,'22-03-30','22-03-31',200),
  ('Ботинки для сноуборда DC Mutiny Charocal',1,'описание',10999,'img/lot-4.jpg',3,'22-03-30','22-03-31',200),
  ('Куртка для сноуборда DC Mutiny Charocal',1,'описание',7500,'img/lot-5.jpg',4,'22-03-30','22-03-31',200),
  ('Маска Oakley Canopy',1,'описание',5400,'img/lot-6.jpg',6,'22-03-30','22-03-31',200);

/*добавьте пару ставок для любого объявления*/
INSERT INTO bets (lot_id,price,user_id,creation_time)
VALUES
(1,11199,1,'05-05-22'),
(1,11399,1,'05-05-22');

/*получить все категории;*/
SELECT * FROM categories;

/*получить самые новые, открытые лоты.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;*/
SELECT
  lots.name,
  lots.begin_price,
  lots.img,
  MAX(price),
  categories.name as category
FROM lots
       left join bets on lots.id = bets.lot_id
       left join categories on lots.category_id = categories.id
group by lots.name,lots.date_completion,lots.begin_price,lots.img,categories.name
order by lots.date_completion;

/*показать лот по его ID. Получите также название категории, к которой принадлежит лот;*/
SELECT * FROM lots
  left join categories on lots.category_id = categories.id
WHERE lots.id=1;

/*обновить название лота по его идентификатору;*/
UPDATE lots SET name = '2015 Rossignol District Snowboard' WHERE id=1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате.*/
SELECT *
FROM bets
WHERE lot_id=1 ORDER BY creation_time;
