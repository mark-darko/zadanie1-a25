<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTransactionRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\Transaction;

class EmployeeController extends Controller
{
    /**
     * Регистрирует нового сотрудника.
     * @param \App\Http\Requests\StoreEmployeeRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    /**
     * Добавляет транзакцию для сотрудника.
     * @param \App\Http\Requests\AddTransactionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addTransaction(AddTransactionRequest $request)
    {
        $validated = $request->validated();

        $hourlyRate = 100;

        $transaction = Transaction::create(array_merge($validated, [
            'amount' => $validated['hours'] * $hourlyRate,
            'date' => now()->toDateString(),
        ]));

        return response()->json($transaction, 201);
    }

    /**
     * Возвращает список всех сотрудников и сколько им еще не выплачено.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function unpaidSalaries()
    {
        $unpaidSalaries = Employee::with(['transactions' => function ($query) {
            $query->where('paid', false);
        }])->get()->mapWithKeys(function ($employee) {
            return [$employee->id => $employee->transactions->sum('amount')];
        });

        return response()->json($unpaidSalaries);
    }

    /**
     * Выплачивает зарплату всем сотрудникам.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function payAllSalaries()
    {
        Transaction::where('paid', false)->update(['paid' => true]);

        return response()->json(['message' => 'All salaries have been paid.']);
    }
}
