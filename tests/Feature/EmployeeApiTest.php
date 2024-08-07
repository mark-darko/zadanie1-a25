<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
   use RefreshDatabase;

    /**
     * Проверяет, что можно создать сотрудника.
     * @return void
     */
    public function test_can_create_employee()
    {
        $response = $this->postJson('/api/employees', [
            'email' => 'test@google.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'email']);
    }

    /**
     * Проверяет, что можно создать транзакцию.
     * @return void
     */
    public function test_can_add_transaction()
    {
        $employee = Employee::factory()->create();

        $response = $this->postJson('/api/transactions', [
            'employee_id' => $employee->id,
            'hours' => 8,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'employee_id', 'hours', 'amount']);
    }

    /**
     * Проверяет, что можно получить список невыплаченных зарплат.
     * @return void
     */
    public function test_can_get_unpaid_salaries()
    {
        $employee = Employee::factory()->create();
        Transaction::factory()->count(3)->create([
            'employee_id' => $employee->id,
            'paid' => false,
        ]);

        $response = $this->postJson('/api/unpaid-salaries');

        $response->assertStatus(200)
            ->assertJsonStructure([$employee->id]);
    }

    /**
     * Проверяет, что можно выплатить все зарплаты.
     * @return void
     */
    public function test_can_pay_all_salaries()
    {
        Transaction::factory()->count(5)->create(['paid' => false]);

        $response = $this->postJson('/api/pay-all-salaries');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('transactions', ['paid' => false]);
    }
}
