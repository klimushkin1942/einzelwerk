<?php

namespace App\Http\Services;

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CounterpartyService extends BaseService
{
    protected string $token;
    protected string $url;

    public function __construct()
    {
        $this->token = config('dadata.token');
        $this->url = config('dadata.url');
    }

    /**
     * Создание контрагента
     *
     * @param array $params
     * @return Counterparty
     * @throws ValidationException|ConnectionException
     */
    public function create(array $params): Counterparty
    {
        $validator = Validator::make($params, [
            'inn' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $this->fetchDataFromDaData($params['inn']);

        if (empty($data)) {
            throw new \RuntimeException('dadata.fetch.failed');
        }

        $mainSuggestion = reset($data['suggestions']);

        $counterpartyData = $this->prepareCounterpartyData($mainSuggestion);

        return Counterparty::create(
            [
                'name' => $counterpartyData['name'],
                'inn' => $counterpartyData['inn'],
                'user_id' => $counterpartyData['user_id'],
                'ogrn' => $counterpartyData['ogrn'],
                'address' => $counterpartyData['address']
            ]
        );
    }

    /**
     * Получение данных из API DaData
     *
     * @param string $inn
     * @return array
     * @throws ConnectionException
     */
    protected function fetchDataFromDaData(string $inn): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->url, [
            'query' => $inn,
            'branch_type' => 'MAIN'
        ]);

        if ($response->failed()) {
            Log::error('Ошибка при запросе к API DaData: ' . $response->body());
            throw new ConnectionException('Ошибка при запросе к API DaData.');
        }

        return $response->json();
    }

    /**
     * Подготовка данных для создания контрагента
     *
     * @param array $data
     * @return array
     */
    protected function prepareCounterpartyData(array $data): array
    {
        return [
            'inn' => $data['data']['inn'],
            'name' => $data['data']['name']['short_with_opf'] ?? null,
            'ogrn' => $data['data']['ogrn'] ?? null,
            'address' => $data['data']['address']['unrestricted_value'] ?? null,
            'user_id' => auth()->id(),
        ];
    }

    /**
     * Получение списка контрагентов
     *
     * @return mixed
     */
    public function getList(): mixed
    {
        return User::with('counterparties')->findOrFail(auth()->id());
    }
}
