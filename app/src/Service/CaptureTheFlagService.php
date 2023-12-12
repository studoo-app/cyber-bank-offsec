<?php

namespace App\Service;

use App\Entity\Operation;
use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class CaptureTheFlagService
{


    public function __construct(
        private readonly string $rootDir,
        private readonly LoggerInterface $ctfLogger,
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function logArray(Array $log): void
    {
        $this->save($log);

        if($log['type'] === "OPERATION"){
            $this->triggerLevel2($log);
        }

    }


    private function save(array $log): void
    {
        $this->ctfLogger->info(json_encode($log));
    }

    public function getFlags(){
        $raw = file_get_contents($this->rootDir."/".$_ENV["CTF_LOG_FILE_PATH"]);
        $flags = [];
        foreach(explode("\n",$raw) as $line){
            if(strlen($line)>0){

                if (preg_match('/\{([^}]+)\}/', $line, $objectResult) && !empty($objectResult[0])) {
                    $resultObject = $objectResult[0];
                    $flags[] = json_decode($resultObject,true);
                }
            }
        }

        return array_filter($flags,function(array $log){
            return array_key_exists("flag",$log);
        });
    }

    // CTF CHECKING

    private function triggerLevel2($log): void
    {
        $shadowAccountNumber = $this->userRepository
            ->findOneBy(["email"=>$_ENV["SHADOW_ACCOUNT_MAIL"]])
            ->getAccount()
            ->getNumber();
        if(
            $shadowAccountNumber === $log["accountNumber"] && $log["balance"] === $log["amount"]
        ) {
            $this->save([
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "flag"=>"CTF_2_FIRST_TRANSFER"
            ]);
        }
    }



}