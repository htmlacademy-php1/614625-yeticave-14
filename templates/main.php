<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $category) :?>
            <li class="promo__item promo__item--<?=htmlspecialchars($category['code'])?>">
                <a class="promo__link" href="/category.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($lots as $lot) :?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($lot['img'])?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($lot['category'])?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="/lot.php?id=<?=htmlspecialchars($lot['id'])?>"><?=htmlspecialchars($lot['name'])?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=formatPrice(htmlspecialchars($lot['begin_price']) )?></span>
                        </div>
                        <?php
                        $dataRange = get_dt_range($lot['date_completion'],date('Y-m-d H:i:s'));
                        ?>
                        <div class="lot__timer timer <?if ($dataRange['hour']<1){echo 'timer--finishing';}?>">
                            <?=sprintf("%02d", $dataRange['hour'])?>:<?=sprintf("%02d", $dataRange['minute'])?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</section>
