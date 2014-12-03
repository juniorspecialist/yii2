<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.11.14
 * Time: 23:07
 */
//отображаем список тв-параметров документа+формируем поля относительно их типов

//массив HTML-элементов, каждый элемент это тв-параметр
if(!empty($tv)){
    //$element[0]-хтмл элемент, $element[1]- его название(label)
    foreach($tv as $element){


        if(isset($element[2])){

?>

            <label>
                <?php echo $element[1]; ?>
                <!--            <br>-->
                <?php echo $element[0]; ?>
            </label>


<?php
        }else{



        ?>

        <div class="form-group field-content">
            <?php echo $element[1]; ?>
<!--            <br>-->
            <?php echo $element[0]; ?>
        </div>
    <?php

        }

    }
}
?>
<style>
    .tv_label_form{
        border: 1px solid red;
        width: 170px;
    }
</style>
