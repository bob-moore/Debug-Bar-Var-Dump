import { Fragment } from "@wordpress/element";
import apiFetch from '@wordpress/api-fetch';
import parse from 'html-react-parser';

const App = function( props : { [ key : string ] : any } )
{
    return (
        <Fragment>
            { props.data.map( ( item ) => {
                return(
                    <div className="dump">
                        <table>
                            <tr>
                                <th>Time</th>
                                <td>{item.time}</td>
                            </tr>
                            <tr>
                                <th>File</th>
                                <td>{item.file}</td>
                            </tr>
                            <tr>
                                <th>Dump</th>
                                <td>{ parse( item.data ) }</td>
                            </tr>
                        </table>
                    </div>
                )
            } ) }
        </Fragment>
    )
}
export default App;