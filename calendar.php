<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
   <form action="calendar.php" method="get">
       <input type="number" name="year">
       <input type="submit" value="Apply">
   </form>
    <?php
    $self = $_SERVER['PHP_SELF'];
    if(!empty($_GET["year"])){
       $year=$_GET["year"]; 
    }else{
       $year=2018; 
    }
    echo $year; 
    $month=1;
    $week=1;
    $day=1;
//    перебор месцев
    for($k=1;$k<=12;$k++){
    $table="<table border='1' cellpadding='0' cellspacing='0'><caption>".date("M",mktime(0,0,0,$month,1,2018))."</caption><tr><th>#</th>";
        $day=1;
 //        названия дней недели: год и месяц не менять!!!!
    for($i=1;$i<=7;$i++){
        $th=date("D",mktime(0,0,0,1,$i,2018));
        $table .='<th>'.$th.'</th>';
    }
    $table .='</tr>';
//        номера недель
    for($i=1;$i<=6;$i++){
//        числа месяца
//        вычисляем сдвиг
        if($i==1){
           $table .= '<tr><th>'.$week.'</th>'; for($shift=1;$shift<date("N",mktime(0,0,0,$month,$day,$year));$shift++){
            $table .= '<td></td>';
            }
//            первая неделя
            if($shift!==1){
                for($j=1;$j<(9-$shift);$j++){
                $table .= '<td><a href="'.$self.'?day='.$day.'&month='.$month.'&year='.$year.'">'.$day.'</a></td>';
                $day++;
                } 
                $table .= '</tr>';
            }else{
                for ($j=1; $j<=7; $j++){            
                    $table .= '<td><a href="'.$self.'?day='.$day.'&month='.$month.'&year='.$year.'">'.$day.'</a></td>';
                    $day++;
                    }
                $table .= '</tr>';
            }
            $week++;
        }else if($i>1 && $i<4) {
            $table .= '<tr><th>'.$week.'</th>';
            for ($j=1; $j<=7; $j++){            
            $table .= '<td><a href="'.$self.'?day='.$day.'&month='.$month.'&year='.$year.'">'.$day.'</a></td>';
            $day++;
            }
            $table .= '</tr>';
            $week++;
        }else if($i>=4 && $day<=cal_days_in_month(CAL_GREGORIAN, $month, $year)){
            $table .= '<tr><th>'.$week.'</th>';
            for ($j=1; $j<=7; $j++){
                if($day>cal_days_in_month(CAL_GREGORIAN, $month, $year)){
                    $table .= '<td></td>';    
                }else{
                    $table .= '<td><a href="'.$self.'?day='.$day.'&month='.$month.'&year='.$year.'">'.$day.'</a></td>';
                    $day++;
                }
            }
            $table .= '</tr>';
            $week++;
            $sun=$day-1;
            if($day>cal_days_in_month(CAL_GREGORIAN, $month, $year) && date("N",mktime(0,0,0,$month,$sun,$year))==7){
                $week++;
            }
            if($day>cal_days_in_month(CAL_GREGORIAN, $month, $year)){
            $week--;
            $i=7;
        }
        }
    }
    $month++;
    $table .= "</table>";
    echo $table;
    echo "<br>";
    }
//    получаем дату ссылки
        if(!empty($_GET["day"]) && !empty($_GET["month"]) && !empty($_GET["year"])){
        $day=$_GET["day"];
        $month=$_GET["month"];
        $year=$_GET["year"];
        $date=$day."_".$month."_".$year;
        }
        if(!empty($date)):
        $temp=file_get_contents(__dir__.'/ivent.txt');
            if($temp!==""){
            $ivent=unserialize($temp);
        }else{$ivent=array();
             }
            if(isset($ivent[$date])): ?>
            <form method="POST">
               <?php 
                echo $date."<br>";
                echo $ivent[$date]; ?>
                <br><h1>Изменить заметку</h1>
                <textarea cols="60" rows="20" name="note"></textarea>
                <p><input type="submit" name="change" value="Изменить"> 
                <a href="calendar.php"> Cancel </a></p>
            </form> 
            <?php 
            if(!empty($_POST["change"])){
            $ivent[$date]=$_POST["note"];
            $temp = serialize($ivent);
            file_put_contents(__dir__.'/ivent.txt',$temp);
            }
        endif;
    if(!isset($ivent[$date])): ?>
                <form method="POST">
                <?php echo $date."<br>"; ?>
                <h1>Добавить заметку</h1>
                <textarea cols="60" rows="20" name="note"></textarea>
                <p><input type="submit" name="add" value="Сохранить"> 
                <a href="calendar.php"> Cancel </a></p>
            </form> 
         <?php
            if(!empty($_POST["add"])){
            $ivent[$date]=$_POST["note"];
            $temp = serialize($ivent);
            file_put_contents(__dir__.'/ivent.txt',$temp);
            }
            endif; 
            endif; 
    ?>  
          
            
</body>
</html>