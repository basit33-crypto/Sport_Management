<?php
 if (strpos($id_kemudahan, 'F2K4') === 0) {//check Dewan ng bola tmpar
            $id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
			$id_kemudahan_to_check[] = 'F2K15';
			$id_kemudahan_to_check[] = 'F2K16';
			$id_kemudahan_to_check[] = 'F2K5';
			$id_kemudahan_to_check[] = 'F2K6';
			$id_kemudahan_to_check[] = 'F2K7';
			$id_kemudahan_to_check[] = 'F2K8';
			$id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
			$id_kemudahan_to_check[] = 'F2K17';
			$id_kemudahan_to_check[] = 'F2K18'; 
			$id_kemudahan_to_check[] = 'F2K9';
			$id_kemudahan_to_check[] = 'F2K10';
			$id_kemudahan_to_check[] = 'F2K11';
			$id_kemudahan_to_check[] = 'F2K12';				
        }
		if (strpos($id_kemudahan, 'F2K2') === 0 ||strpos($id_kemudahan, 'F2K13') === 0 ) {// jaringstakraw
		    $id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
            $id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
			$id_kemudahan_to_check[] = 'F2K15';
			$id_kemudahan_to_check[] = 'F2K16';
			$id_kemudahan_to_check[] = 'F2K5';
			$id_kemudahan_to_check[] = 'F2K6';
			$id_kemudahan_to_check[] = 'F2K7';
			$id_kemudahan_to_check[] = 'F2K8';			
        }
		if (strpos($id_kemudahan, 'F2K3') === 0 ||strpos($id_kemudahan, 'F2K14') === 0 ) {// jaringstakraw
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
		    $id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
			$id_kemudahan_to_check[] = 'F2K17';
			$id_kemudahan_to_check[] = 'F2K18'; 
			$id_kemudahan_to_check[] = 'F2K9';
			$id_kemudahan_to_check[] = 'F2K10';
			$id_kemudahan_to_check[] = 'F2K11';
			$id_kemudahan_to_check[] = 'F2K12';			
        }
		if (strpos($id_kemudahan, 'F2K15') === 0 ) {//badninton A
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
		}
		if (strpos($id_kemudahan, 'F2K16') === 0 ) {//badninton B
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
		}
		if (strpos($id_kemudahan, 'F2K17') === 0 ) {//badninton C
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
		}
		if (strpos($id_kemudahan, 'F2K18') === 0 ) {//badninton D
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
		}
		
		if (strpos($id_kemudahan, 'F2K5') === 0 || strpos($id_kemudahan, 'F2K6') === 0) {//pp AB
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
			$id_kemudahan_to_check[] = 'F2K15';
		}
		if (strpos($id_kemudahan, 'F2K7') === 0 || strpos($id_kemudahan, 'F2K8') === 0) {//pp CD
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K2';
			$id_kemudahan_to_check[] = 'F2K13';
			$id_kemudahan_to_check[] = 'F2K16';
		}
		if (strpos($id_kemudahan, 'F2K9') === 0 || strpos($id_kemudahan, 'F2K10') === 0) {//pp EF
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
			$id_kemudahan_to_check[] = 'F2K17';
		}
		if (strpos($id_kemudahan, 'F2K11') === 0 || strpos($id_kemudahan, 'F2K12') === 0) {//pp GH
			$id_kemudahan_to_check[] = 'F2K1';
			$id_kemudahan_to_check[] = 'F2K4';
			$id_kemudahan_to_check[] = 'F2K3';
			$id_kemudahan_to_check[] = 'F2K14';
			$id_kemudahan_to_check[] = 'F2K18';
		}
?>