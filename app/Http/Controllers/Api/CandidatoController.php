<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Candidato as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request as EntityRequest;
use Illuminate\Support\Str;
use App\Helpers\LoadFileHelper as LoadFile;
use App\Exports\Candidato as exportClassFile;
use Maatwebsite\Excel\Facades\Excel;

class CandidatoController extends Controller
{
   public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $includes= $request->input('includes') ? $request->input('includes') : [];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            }
            if ($search) {
                $query->where("nombre", "LIKE", "%".$search."%")
                ->orWhere("apellido", "LIKE", "%".$search."%");
            }

            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);
            // $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de candidatos',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los Candidatos');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function show($id)
    {
        try {
            $entity=Model::where('id' , $id)->first();
            if (!$entity) {
                throw new \Exception('Candidato no encontrado', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al consultar el Candidato');
        }//catch
        return $this->response($response, $code ?? 200);
    }//show()

    public function store(EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            $random = Str::random(40);
            $image=$request->foto;
            if ($this->startsWith($request->foto, 'data:image')) {
                //base 64
                $default_size = [
                    'imagesize' => [
                        'width' => 1024,
                        'height' => 768,
                        'quality'=>80
                    ]
                ];
                $size = json_decode(json_encode($default_size));

                // 0. Make the image
                $image = \Image::make($image);
                // resize and prevent possible upsizing

                $image->resize($size->imagesize->width, $size->imagesize->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $endFile = 'jpg';
                if(Str::startsWith($request->foto, 'data:image/png;'))
                  $endFile = 'png';
                $destination_path="candidatos/".$random.".".$endFile;
                \Storage::disk("publicmedia")->put($destination_path, $image->stream($endFile, $size->imagesize->quality));
          
                $data['foto'] = $destination_path;
            }
            //Get last eleccion
            $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
            $data['eleccion_id']=$eleccion->id;
            //Create entity
            $entity=Model::create($data);
            //isset partidos_politicos
            if(isset($data['partidos_politicos'])){
                foreach($data['partidos_politicos'] as $partido_politico_id){
                    $entity->partidos()->create([
                        "partido_politico_id"=>$partido_politico_id
                    ]);
                }
            }
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de candidato exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de candidato no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);
    }//

    public function update($id, EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Candidato no encontrado', 404);
            }
            //Update data
            $entity->update($data);
            //isset partidos_politicos
            if(isset($data['partidos_politicos'])){
                $entity->partidos()->delete();
                foreach($data['partidos_politicos'] as $partido_politico_id){
                    $entity->partidos()->create([
                        "partido_politico_id"=>$partido_politico_id
                    ]);
                }
            }else{
                $entity->partidos()->delete();
            }
            DB::commit();
            $response = $this->getSuccessResponse($entity , 'Actualización exitosa' );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, "La actualización de datos ha fallado");
        }//catch
        return $this->response($response, $code ?? 200);
    }//update()

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $entity=Model::find($id);
            $this->validModel($entity, 'Candidato no encontrado');
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar el Candidato";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()

    public function updateImage($id,EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            $random = Str::random(40);
            $image=$request->foto;
            if ($this->startsWith($request->foto, 'data:image')) {
                //base 64
                $default_size = [
                    'imagesize' => [
                        'width' => 1024,
                        'height' => 768,
                        'quality'=>80
                    ]
                ];
                $size = json_decode(json_encode($default_size));

                // 0. Make the image
                $image = \Image::make($image);
                // resize and prevent possible upsizing

                $image->resize($size->imagesize->width, $size->imagesize->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $endFile = 'jpg';
                if(Str::startsWith($request->foto, 'data:image/png;'))
                  $endFile = 'png';
                $destination_path="candidatos/".$random.".".$endFile;
                \Storage::disk("publicmedia")->put($destination_path, $image->stream($endFile, $size->imagesize->quality));
          
                $data['foto'] = $destination_path;
            }
            $entity=Model::where('id',$id)->update($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Actualización de imagen exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de usuario no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);
    }//

    function startsWith ($string, $startString)
    {
      $len = strlen($startString);
      return (substr($string, 0, $len) === $startString);
    }

    public function excel(Request $request){
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte_'.$now.'.xlsx';
        return Excel::download(new exportClassFile(), $excelName);
    }//
}
