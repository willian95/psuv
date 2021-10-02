<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExportJob;
use App\Models\Elector;

use Rap2hpoutre\FastExcel\FastExcel;

class RepJobExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rep:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export large REP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $pendingJobs = ExportJob::where("status", "not started")->get();
  
        foreach($pendingJobs as $job){
           
            try{
                $pendingJob = ExportJob::find($job->id);
                $pendingJob->status = "processing";
                $pendingJob->update();

                /*if($pendingJob->entity == "municipios"){
                    $data = Elector::where("municipio_id", $pendingJob->entity_id)->with("municipio", "parroquia","centroVotacion")->get();
                }else{
                    $data = Elector::with("municipio", "parroquia","centroVotacion")->get();
                }

                (new FastExcel($data))->export(public_path()."/excel/".$pendingJob->pid."REP.xlsx", function ($user) {
                    return [
                        'NACIONALIDAD' => $user->nacionalidad,
                        'CEDULA' => $user->cedula,
                        'PRIMER APELLIDO' => $user->primer_apellido,
                        'SEGUNDO APELLIDO' => $user->segundo_apellido,
                        'PRIMER NOMBRE' => $user->primer_nombre,
                        'SEGUNDO NOMBRE' => $user->segundo_nombre,
                        'SEXO' => $user->fn,
                        'ESTADO' => "FALCÓN",
                        'MUNICIPIO' => $user->municipio->nombre,
                        'PARROQUIA' => $user->parroquia->nombre,
                        'CENTRO VOTACION' => $user->centroVotacion->nombre,
                    ];
                });

                $pendingJob->status = "finished";
                $pendingJob->update();*/

                $url = url("/excel/".$pendingJob->pid."REP.xlsx");
                $this->sendEmail($url, $pendingJob->email);

            }catch(\Exception $e){

                $pendingJob = ExportJob::find($job->id);
                $pendingJob->status = "not started";
                $pendingJob->update();
                dd($e->getMessage(), $e->getLine());
            }

        }

    }

    function sendEmail($url, $email){

        $data = ["url" => $url];
        $to_email = $email;

       

        \Mail::send("emails.sendREP", $data, function($message) use ($to_email) {
            dump(env("MAIL_USERNAME"), env("MAIL_PASSWORD"), $to_email);
            $message->to($to_email, "Usuario")->subject("¡Tu archivo está listo!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });

    }
}
