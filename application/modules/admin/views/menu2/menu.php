<?php 

	function bilangan($col='1', $row='1'){

		$jum_data = ($col*$row);
		$batas = "999999999999999999999999999999999999999";
		$arr = [];
		for($angka=1;$angka<=$batas;$angka++)
        {
			//bilangan prima
            $prima = true;
            for($i=2; $i<$angka;$i++) {
                if($angka%$i == 0){
                    $prima = false;
                }
			}

			//tampung bilangan prima
			if($prima){
				$arr[] = $angka;
			}

			//stop bilangan prima jika sudah sampai jumlah data
			if(count($arr) == $jum_data){
				break;
			}
        }

		//menyusun baris
		$kelipatan = $col;
		$i=0;
		foreach($arr as $val){
			if($i % $kelipatan == 0){
				echo "<br>";
			}
			echo $val.',';
			$i++;
		}

	}
	
	//bilangan prima
	bilangan(4,3);
	echo "<br><br>";
	bilangan(5,5);
	
?>