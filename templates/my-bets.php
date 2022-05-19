<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category) :?>
            <li class="nav__item">
                <a href="/category.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a>
            </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($bets as $bet):?>
        <?php $dataRange = get_dt_range($bet['date_completion'],date('Y-m-d H:i:s'));?>
        <tr class="rates__item <?php if($bet['winner_id'] === $_SESSION['user_id']){echo 'rates__item--win';} elseif( $dataRange['hour']==0 && $dataRange['minute']==0){echo 'rates__item--end';}?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$bet['img']?>" width="54" height="40" alt="<?=$bet['lots_name']?>">
            </div>
            <div>
            <h3 class="rates__title"><a href="/lot.php?id=<?=$bet['id']?>"><?=$bet['lots_name']?></a></h3>
            <?php if ($bet['contact'] && $bet['winner_id'] === $_SESSION['user_id']):?>
              <p><?=$bet['contact']?></p>
            <?php endif;?>
            </div>
          </td>
          
          <td class="rates__category"><?=$bet['category_name']?></td>
            <?php if($bet['winner_id'] === $_SESSION['user_id']):?>
                <td class="rates__timer">
                    <div class="timer timer--win">Ставка выиграла</div>
                </td>
            <?php elseif ($dataRange['hour']==0 && $dataRange['minute']==0):?>
            <td class="rates__timer">
                <div class="timer timer--end">Торги окончены</div>
            </td>
            <?php else:?>
                <td class="rates__timer">
                    <div class="timer <?if ($dataRange['hour']<1){echo 'timer--finishing';}?>">
                        <?=$dataRange['hour']?>:<?=$dataRange['minute']?>:<?=$dataRange['seconds']?>
                    </div>
                </td>
            <?php endif;?>
          <td class="rates__price">
            <?=formatPrice($bet['price'])?>
          </td>
          <td class="rates__time">
            <?php 
            //должна быть разница по времени всяко
            $publishDate = get_dt_range(date('Y-m-d'), $bet['creation_time']);
            // print_r('<pre>');
            // var_dump($publishDate);
            // print_r('</pre>');
            ?>
            <?//=$bet['creation_time']?>
            <?php 
            $formWord = get_noun_plural_form(57, 'минут', 'минуты', 'минут');
            echo '3 ' . $formWord . ' назад';

            echo '<br>';

            $formWord = get_noun_plural_form(1, 'час', 'часа', 'часов');
            echo '1 ' . $formWord . ' назад'; 
            
            echo '<br>';

            echo 'Вчера, в ' . date('H:m');

            echo '<br>';

            echo date('d.m.Y') . ' в ' . date('H:m');

            //var_dump($printDate);
            if ($publishDate['hour']<1){
                $printDate = get_noun_plural_form($publishDate['minute'], 'минута', 'минуты', 'минут');
                //var_dump($printDate);
            }
            if ($publishDate['hour']>1 && $publishDate['hour']<24){

            }
            if ($publishDate['hour']>24 && $publishDate['hour']<48) {

            }
            if ($publishDate['hour']>48) {

            }
            ?>
          </td>
        </tr>
        <?endforeach;?>
      </table>
    </section>
  </main>