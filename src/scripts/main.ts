import domReady from '@wordpress/dom-ready';
import apiFetch from '@wordpress/api-fetch';
import { createElement, render, Fragment } from "@wordpress/element";
import App from './components/App';

domReady( () => {

    const container = document.getElementById( 'debug-bar-var-dump' );

    const _render = ( data ) =>
    {
        if ( data )
        {
            try 
            {
                render(
                    createElement( App, { data : JSON.parse( data ) } ),
                    container
                );
            }
            catch( e )
            {
                console.log(e);
            }
        }
    }

    if ( container )
    {
        apiFetch( { path : 'debugbar/v2/vardumps' } ).then( _render );

    }
} );