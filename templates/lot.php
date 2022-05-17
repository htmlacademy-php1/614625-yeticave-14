<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['name'])?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?=htmlspecialchars($lot['img'])?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name'])?>">
            </div>
            <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category'])?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lot['description'])?></p>
        </div>
        <div class="lot-item__right">
            <?php $dataRange = get_dt_range($lot['date_completion'],date('Y-m-d H:i:s'));
            if($dataRange['hour'] !==0 && $dataRange['minute'] !==0):
                if(isset($_SESSION['user_id'])):
                    if($_SESSION['user_id'] !== $lot['user_id']):?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?php if ($dataRange['hour']<1){echo 'timer--finishing';}?>">
                    <?=$dataRange['hour']?>:<?=$dataRange['minute']?>
                </div>

                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=$bet?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=formatPrice($bidStep)?></span>
                    </div>
                </div>

                <form class="lot-item__form" action="" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?php if ($error){echo 'form__item--invalid';}?>">
                        <label for="price">Ваша ставка</label>
                        <input id="price" type="text" name="price" placeholder="<?=$bidStep?>">
                        <span class="form__error"><?=$error?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>

            </div>
            <?php endif;
            endif;
        endif;?>
            <div class="history">
                <h3>История ставок (<span>10</span>)</h3>
                <table class="history__list">
                    <tr class="history__item">
                        <td class="history__name">Иван</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">5 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Константин</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">20 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Евгений</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">Час назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Игорь</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 08:21</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
