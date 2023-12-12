<?php

namespace App\Service;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class CaptureTheFlagService
{

    const FLAG_API_PAGE_FOUND = "CTF_API_PAGE_FOUND";
    const FLAG_TRANSFER_PAGE_FOUND = "CTF_TRANSFER_PAGE_FOUND";
    const FLAG_FIRST_TRANSFER = "CTF_FIRST_TRANSFER";
    const FLAG_AMOUNT_OBJECTIVE_COMPLETE = "CTF_AMOUNT_OBJECTIVE_COMPLETE";
    const FLAG_SUSPICIOUS_TRANSFER = "CTF_SUSPICIOUS_TRANSFER";
    const FLAG_INTRUSION_DETECTED = "CTF_INTRUSION_DETECTED";

    public function __construct(
        private readonly string          $rootDir,
        private readonly LoggerInterface $ctfLogger,
        private readonly UserRepository  $userRepository,
    )
    {
    }

    public function logArray(array $log): void
    {
        $this->save($log);
        $this->triggerCTFs($log);
    }

    public function getFlags(): array
    {
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


    private function save(array $log): void
    {
        $this->ctfLogger->info(json_encode($log));
    }


    // CTF CHECKING

    private function isFlagAlreadyExist(string $flag): bool
    {
        $flags = $this->getFlags();
         return count(array_filter($flags,function(array $item) use ($flag){
             return $item['flag'] === $flag;
         })) === 1;

    }

    private function triggerCTFs(array $log){
        if($log['type'] === "OPERATION"){
            $this->triggerFirstTransferCTF($log);
            $this->triggerTransferAmountObjectiveCTF();
        }

        if($log['type'] === "ACCESS"){
            $this->triggerTransferPageFoundCTF($log);
            $this->triggerApiPageFoundCTF($log);
        }

        if($log['type'] === "ALERT"){
            $this->triggerSuspiciousTransferCTF();
            $this->triggerIntrusionDetectedCTF();
        }
    }

    private function triggerTransferPageFoundCTF(array $log): void
    {
        if( $log['type'] === 'ACCESS' &&
            $log['path'] === '/admin/transfer' &&
            !$this->isFlagAlreadyExist(self::FLAG_TRANSFER_PAGE_FOUND)
        ){
            $this->save([
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "flag"=>self::FLAG_TRANSFER_PAGE_FOUND
            ]);
        }

    }

    private function triggerApiPageFoundCTF(array $log): void
    {
        if( $log['type'] === 'ACCESS' &&
            $log['path'] === '/api/extract' &&
            !$this->isFlagAlreadyExist(self::FLAG_API_PAGE_FOUND)
        ){
            $this->save([
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "flag"=>self::FLAG_API_PAGE_FOUND,
            ]);
        }
    }


    private function triggerFirstTransferCTF($log): void
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
                "flag"=>self::FLAG_FIRST_TRANSFER
            ]);
        }
    }

    private function triggerTransferAmountObjectiveCTF()
    {
        $shadowAccount = $this->userRepository
            ->findOneBy(["email"=>$_ENV["SHADOW_ACCOUNT_MAIL"]])->getAccount();

        if(
            $shadowAccount->getBalance() >= $_ENV["CTF_OBJECTIVE"] &&
            !$this->isFlagAlreadyExist(self::FLAG_AMOUNT_OBJECTIVE_COMPLETE)
        ){
            $this->save([
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "flag"=>self::FLAG_AMOUNT_OBJECTIVE_COMPLETE
            ]);
        }

    }

    private function triggerSuspiciousTransferCTF(){
        $this->save([
            "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
            "flag"=>self::FLAG_SUSPICIOUS_TRANSFER
        ]);
    }

    private function triggerIntrusionDetectedCTF(): void
    {
        $flags = $this->getFlags();
        $isLimitReached = count(array_filter($flags,function(array $item){
                return $item['flag'] === self::FLAG_SUSPICIOUS_TRANSFER;
            })) === 5;

        if($isLimitReached){
            $this->save([
                "date"=>(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Paris')))->format('d/m/Y H:i:s'),
                "flag"=>self::FLAG_INTRUSION_DETECTED
            ]);
        }
    }
}