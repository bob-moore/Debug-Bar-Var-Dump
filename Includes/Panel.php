<?php

if ( class_exists( 'Debug_Bar_Panel' ) )
{
    class Devkit_Debugbar_Dump_Panel extends \Debug_Bar_Panel
    {
        public function init() 
        {
            $this->title( __( 'Dumps', 'devkit-debug-bar' ) );
        }

        public function render()
        {
            echo '<div id="debug-bar-var-dump"></div>';
        }
    }
}
