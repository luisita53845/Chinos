<?php 
 
namespace App\Http\Controllers;  
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use App\MediaType;

class MediaTypeController extends Controller 
{     
    public function showmass(){      

        return view("media-types.insert-mass");     
    } 
 
    public function storemass(Request $r){


        $repetidos = [];


        $reglas =[
            'media-types' => 'required|mimes:csv,txt'
        ];


        $validador = Validator::make($r->all(), $reglas);


        if ($validador->fails()) {

            //enviar mensaje de error de la validacion de la vista
            return redirect('media-types/insert')->withErrors($validador);
        }else{
            //Transladar el archivo cargado a Storage
            $r->file('media-types')->storeAs('media-types' , $r->file('media-types')->getClientOriginalName());


            $ruta = base_path().'\storage\app\media-types\\'.$r->file('media-types')->getClientOriginalName();

            if ( ($puntero = fopen($ruta , 'r')) !== false){

                $contadora = 0;

                while( ($linea = fgetcsv($puntero)) !== false) {
                    

                    $conteo = MediaType::where('Name' , '=' , $linea[0])->get()->count();


                    if ($conteo == 0) {


                        $m = new MediaType();

                        $m->Name = $linea[0];

                        $m->save();

                        $contadora++;

                    }else{

                        //Agregar una casilla al arreglo repetidos
                        $repetidos[] = $linea[0];
                    }
                }
                
                //TODO: poner mensaje de grabación de carga masiva en la vista

                if ( count( $repetidos ) == 0 ) {
                    return redirect('media-types/insert')->with('exito' , "Carga masiva realizada, Registros ingresados: {$contadora}");
                }else{
                    return redirect('media-types/insert')->with('exito' , "Carga masiva con las siguientes excepciones:")
                ->with("repetidos" , $repetidos);
                }   
            }
        }
    }
} 
        