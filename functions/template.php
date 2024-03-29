<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция округляет число и добавляет знак рубля
 * @param int $price число
 * @return string цена с добавлением знака ₽
 */
function formatPrice(int $price) :string
{
    if ($price<1000){
        return ceil($price) . ' ₽';
    }
    $price = ceil($price);
    return number_format($price, 0, null, ' ') . ' ₽';
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * функция вовзращает разницу даты текущего времени и даты, когда сделана ставка в человекочитаемом виде
 * @param array $publishDate массив времени
 * @param string строка с датой создания ставки
 * @return string строку с человекочитаемым видом разницы дат 
 */
function humanTime(array $publishDate,string $creationTime) : string
{   
    if ($publishDate['hour']<1){
        $formWord = get_noun_plural_form($publishDate['minute'], 'минута', 'минуты', 'минут');
        return $publishDate['minute'] . " " . $formWord . ' назад';
    }
    if ($publishDate['hour']>1 && $publishDate['hour']<24){
        $formWord = get_noun_plural_form(1, 'час', 'часа', 'часов');
        return $publishDate['hour'] . " " . $formWord . ' назад'; 
    }
    if ($publishDate['hour']>24 && $publishDate['hour']<48) {
        $date = date_create($creationTime); 
        return 'Вчера, в ' . date_format($date, 'H:m');
    }
    if ($publishDate['hour']>48) {
        $date = date_create($creationTime); 
        return date_format($date, 'd.m.y') . ' в ' . date_format($date, 'H:m');
    }
}