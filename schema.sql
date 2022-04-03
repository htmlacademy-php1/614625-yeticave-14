create DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;

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
CREATE TABLE yeticave.categories  (
  id int PRIMARY KEY AUTO_INCREMENT,
  name varchar(11) NOT NULL,
  characterCode varchar(11) NOT NULL
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
CREATE TABLE yeticave.lots (
   id int(11) PRIMARY KEY AUTO_INCREMENT,
   dataCreate date NOT NULL,
   description varchar (11),
   img varchar (11),
   beginPrice int(11),
   dateCompletion date,
   bidStep int(11)
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
CREATE TABLE yeticave.bets (
   id int(11) PRIMARY KEY AUTO_INCREMENT,
   date date NOT NULL,
   price int(11)
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
    созданные лоты;
    ставки.
    ставки
*/
CREATE TABLE yeticave.users (
  id int(11) PRIMARY KEY AUTO_INCREMENT,
  dateRegistration date NOT NULL,
  email varchar(11),
  password varchar(11),
  contact varchar(11)
)



