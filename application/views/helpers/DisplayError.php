<?php

class Zend_View_Helper_DisplayError {

    public function DisplayError(array $msg = null) {
        $return = "";

        if ($msg) {
            foreach ($msg as $m) {

                switch ($m[1]) {
                    case 0:
                        $class = "red-msg";
                        break;
                    case 1:
                        $class = "green-msg";
                        break;
                    default:
                        $class = "gray-msg";
                        break;
                }

                $return .= '<h2 id="system-message" class="' . $class . ' grid_12"><span> ' . $m[0] . '</span><a href="#" id="btn-msg-close" title="Fechar">X</a></h2>
				<script>
                                    $("#btn-msg-close").click(function() {
                                            $("#system-message").fadeOut(500);
                                            return false;
                                    });
				</script>';
            }
        }


        return $return;
    }

}

?>
