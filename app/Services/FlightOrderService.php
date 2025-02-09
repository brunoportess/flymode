<?php

namespace App\Services;

use App\Repository\FlightOrderRepositoryInterface;
use Carbon\Carbon;
use MailerSend\Exceptions\MailerSendException;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;

class FlightOrderService implements FlightOrderServiceInterface
{
    private FlightOrderRepositoryInterface $flightOrderRepository;

    public function __construct(FlightOrderRepositoryInterface $flightOrderRepository)
    {
        $this->flightOrderRepository = $flightOrderRepository;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    function getAll(): mixed
    {
        try {
            return $this->flightOrderRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function getById($id): mixed
    {
        try {
            return $this->flightOrderRepository->getById($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    function store($request): mixed
    {
        try {
            $user = auth()->user();
            $item = $request;
            $item['user_id'] = $user->id;
            $item['solicitante'] = $user->name;
            $item['status'] = 'solicitado';
            $item['status_codigo'] = 's';
            return $this->flightOrderRepository->store($item);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function update($request, $id): mixed
    {
        try {
            return $this->flightOrderRepository->update($request, $id);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function destroy($id): mixed
    {
        try {
            return $this->flightOrderRepository->destroy($id);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $item
     * @param $id
     * @param $status
     * @return \Exception|mixed
     */
    function statusUpdate($item, $id, $status): mixed
    {
        try {

            if($this->changeStatusValidate($item, $status)) {
                $updateItem = [
                    'status' => $status
                ];
                $response = $this->update($updateItem, $id);
                // se atualizou tudo certo, envia email de notificação
                if($response) {
                    $this->emailNotification($item->request_user->email, $id, $status);
                }
                return $response;
            }
            throw new \Exception("Não foi possível alterar o status do pedido");
        } catch (\Exception $e) {

            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $item
     * @param $status
     * @return bool
     * @throws \Exception
     */
    private function changeStatusValidate($item, $status): bool
    {
        $statusItem = $item->status;
        $hoje = Carbon::now();
        $dataIda = $item->data_ida;

        // ATUALIZA PARA APROVADO SE A DATA DE IDA FOR DATA A PARTIR DO DIA SEGUINTE
        if($status == 'aprovado') {
            //SE O PEDIDO JA ESTIVER CANCELADO, NAO PODE RETORNAR O STATUS PARA APROVADO
            if($statusItem == 'cancelado') {
                throw new \Exception('Pedido já se encontra cancelado e não pode ser aprovado!');
            }
            // SE A DATA DE IDA FOR HOJE OU ANTERIOR, PROIBE ALTERAR O STATUS
            if($dataIda <= $hoje->format('Y-m-d')) {
                throw new \Exception('Pedido não pode ser aprovado com menos de 24h do pedido');
            }
            return true;
        } elseif($status == 'cancelado') {
            // SE TENTAR CANCELAR UM PEDIDO JÁ APROVADO E EM CIMA DA HORA (-24HS)
            if($statusItem == 'aprovado' && $dataIda <= $hoje->format('Y-m-d')) {
                throw new \Exception('Pedido não pode ser cancelado com menos de 24h do pedido');
            }
            return true;
        }

        return false;
    }

    /**
     * @param $status
     * @return mixed
     * @throws \Exception
     */
    function getByStatus($status): mixed
    {
        try {
            return $this->flightOrderRepository->getByStatus($status);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    function busca($data): mixed
    {
        try {
            return $this->flightOrderRepository->busca($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function emailNotification($email, $id, $status)
    {
        try {
            $mailersend = new MailerSend(['api_key' => 'mlsn.0aac6f360c3ef2f6703ac516c4f468c8fced327df7157a8983040b3020f0249b']);
            $personalization = [
                new Personalization($email, [
                    'status_text' => $status,
                    'order_number' => $id
                ])
            ];
            $recipients = [
                new Recipient($email, 'Recipient'),
            ];
            $emailParams = (new EmailParams())
                ->setFrom('test@trial-pxkjn41v8594z781.mlsender.net')
                ->setFromName('Bruno')
                ->setRecipients($recipients)
                ->setSubject('Atualzação de status')
                ->setTemplateId('z3m5jgr1mvd4dpyo')
                ->setPersonalization($personalization);

            $mailersend->email->send($emailParams);
        } catch (MailerSendException $e) {
            throw new \Exception($e->getMessage());
        }

    }
}
