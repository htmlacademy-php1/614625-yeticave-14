<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) :?>
            <li class="nav__item <?=htmlspecialchars($category['code'])?>">
                <a href="pages/all-lots.html"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=$_GET['search']?></span>»</h2>
        <ul class="lots__list">
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
                            <?=$dataRange['hour']?>:<?=$dataRange['minute']?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
        </ul>
      </section>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
  </main>