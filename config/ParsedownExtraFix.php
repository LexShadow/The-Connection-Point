<?php
/*
			ParsedownExtraFix
			   LexShadow
*/


class ParsedownExtraFix extends ParsedownExtra {
    const version = '0.0.0.1';
	
    function __construct(){
        if (version_compare(parent::version, '0.8.1') < 0){
            throw new Exception('ParsedownExtra requires a later version of Parsedown');
        }
    }
    protected function blockListComplete(array $Block){
        $list = parent::blockListComplete($Block);
        if (!isset($list)) {
            return null;
        }
        foreach ($list['element'] as $key => $listItem) {
			if(is_array($listItem)){
				foreach($listItem as $inList => $items){
					$firstThree = substr($items['text'][0], 0, 3);
					$rest = trim(substr($items['text'][0], 3));
					if ($firstThree === '[x]' || $firstThree === '[ ]') {
						$hex = substr($items['text'][0], strpos($items['text'][0], '{hex}')+5);
						$hex = substr($hex, 0, strpos($hex, '{/hex}'));
						if($hex != ""){
								$rest = str_replace("{hex}" . $hex . "{/hex}", "", $rest);
								$hex = ' style="color:' . $hex .';" ';
						}
						$checked = $firstThree === '[x]' ? ' <i class="fas fa-check-square"' . $hex . '></i> ' : '<i class="far fa-square"' . $hex . '></i> ';
						$list['element'][$key][$inList]['attributes']['class'] = 'task-list-item';
						$list['element'][$key][$inList]['text'][0] = $checked . $rest;
					}
				}
			}
        }

        return $list;
    }
}

?>