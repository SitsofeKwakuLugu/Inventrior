<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionButtonsTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $company;
    protected $companyAdmin;
    protected $staff;
    protected $product;

    public function setUp(): void
    {
        parent::setUp();

        // Create a company
        $this->company = Company::create([
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'verified' => true,
        ]);

        // Create super admin
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('Password123!'),
            'role' => 'super-admin',
        ]);

        // Create company admin
        $this->companyAdmin = User::create([
            'name' => 'Company Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('Password123!'),
            'company_id' => $this->company->id,
            'role' => 'company-admin',
        ]);

        // Create staff
        $this->staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@test.com',
            'password' => bcrypt('Password123!'),
            'company_id' => $this->company->id,
            'role' => 'staff',
        ]);

        // Create product
        $this->product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'company_id' => $this->company->id,
        ]);
    }

    // ===== PRODUCT TESTS =====
    
    public function test_company_admin_can_view_products()
    {
        $response = $this->actingAs($this->companyAdmin)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Products');
    }

    public function test_company_admin_can_edit_product()
    {
        $response = $this->actingAs($this->companyAdmin)->get(route('products.edit', $this->product->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Product');
    }

    public function test_company_admin_can_update_product()
    {
        $response = $this->actingAs($this->companyAdmin)->put(
            route('products.update', $this->product->id),
            [
                'name' => 'Updated Product',
                'description' => 'Updated Description',
            ]
        );
        
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'name' => 'Updated Product',
        ]);
        $response->assertRedirect(route('products.index'));
    }

    public function test_company_admin_can_delete_product()
    {
        $response = $this->actingAs($this->companyAdmin)->delete(
            route('products.destroy', $this->product->id)
        );
        
        $this->assertDatabaseMissing('products', [
            'id' => $this->product->id,
        ]);
        $response->assertRedirect(route('products.index'));
    }

    // ===== STAFF TESTS =====
    
    public function test_company_admin_can_view_staff()
    {
        $response = $this->actingAs($this->companyAdmin)->get(route('staff.index'));
        $response->assertStatus(200);
        $response->assertSee('Staff Management');
    }

    public function test_company_admin_can_edit_staff()
    {
        $response = $this->actingAs($this->companyAdmin)->get(route('staff.edit', $this->staff->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Staff Member');
    }

    public function test_company_admin_can_update_staff()
    {
        $response = $this->actingAs($this->companyAdmin)->put(
            route('staff.update', $this->staff->id),
            [
                'name' => 'Updated Staff Name',
                'email' => 'updated@test.com',
            ]
        );
        
        $this->assertDatabaseHas('users', [
            'id' => $this->staff->id,
            'name' => 'Updated Staff Name',
        ]);
        $response->assertRedirect(route('staff.index'));
    }

    public function test_company_admin_can_delete_staff()
    {
        $response = $this->actingAs($this->companyAdmin)->delete(
            route('staff.destroy', $this->staff->id)
        );
        
        $this->assertSoftDeleted('users', [
            'id' => $this->staff->id,
        ]);
        $response->assertRedirect(route('staff.index'));
    }

    public function test_company_admin_can_toggle_staff_status()
    {
        $response = $this->actingAs($this->companyAdmin)->post(
            route('staff.toggle-status', $this->staff->id)
        );
        
        // Staff should be soft deleted after toggle
        $this->assertNotNull($this->staff->fresh()->deleted_at);
        $response->assertRedirect(route('staff.index'));
    }

    // ===== ADMIN TESTS =====
    
    public function test_company_admin_can_view_admins()
    {
        $response = $this->actingAs($this->companyAdmin)->get(route('admin.index'));
        $response->assertStatus(200);
        $response->assertSee('Admin Management');
    }

    public function test_company_admin_can_edit_admin()
    {
        $otherAdmin = User::create([
            'name' => 'Other Admin',
            'email' => 'otheradmin@test.com',
            'password' => bcrypt('Password123!'),
            'company_id' => $this->company->id,
            'role' => 'company-admin',
        ]);

        $response = $this->actingAs($this->companyAdmin)->get(route('admin.edit', $otherAdmin->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Administrator');
    }

    public function test_company_admin_can_update_admin()
    {
        $otherAdmin = User::create([
            'name' => 'Other Admin',
            'email' => 'otheradmin@test.com',
            'password' => bcrypt('Password123!'),
            'company_id' => $this->company->id,
            'role' => 'company-admin',
        ]);

        $response = $this->actingAs($this->companyAdmin)->put(
            route('admin.update', $otherAdmin->id),
            [
                'name' => 'Updated Admin Name',
                'email' => 'updatedadmin@test.com',
            ]
        );
        
        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'name' => 'Updated Admin Name',
        ]);
        $response->assertRedirect(route('admin.index'));
    }

    public function test_company_admin_can_delete_admin()
    {
        $otherAdmin = User::create([
            'name' => 'Other Admin',
            'email' => 'otheradmin@test.com',
            'password' => bcrypt('Password123!'),
            'company_id' => $this->company->id,
            'role' => 'company-admin',
        ]);

        $response = $this->actingAs($this->companyAdmin)->delete(
            route('admin.destroy', $otherAdmin->id)
        );
        
        $this->assertSoftDeleted('users', [
            'id' => $otherAdmin->id,
        ]);
        $response->assertRedirect(route('admin.index'));
    }

    public function test_company_admin_cannot_delete_last_admin()
    {
        $response = $this->actingAs($this->companyAdmin)->delete(
            route('admin.destroy', $this->companyAdmin->id)
        );
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('users', [
            'id' => $this->companyAdmin->id,
        ]);
    }

    // ===== COMPANY TESTS =====
    
    public function test_super_admin_can_view_companies()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('System Administration');
    }

    public function test_super_admin_can_edit_company()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('companies.edit', $this->company->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Company');
    }

    public function test_super_admin_can_update_company()
    {
        $response = $this->actingAs($this->superAdmin)->put(
            route('companies.update', $this->company->id),
            [
                'name' => 'Updated Company Name',
                'email' => 'updated@company.com',
            ]
        );
        
        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
            'name' => 'Updated Company Name',
        ]);
        $response->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_super_admin_can_delete_company_with_no_users()
    {
        // Create a new company with no users
        $emptyCompany = Company::create([
            'name' => 'Empty Company',
            'email' => 'empty@test.com',
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(
            route('companies.destroy', $emptyCompany->id)
        );
        
        $this->assertDatabaseMissing('companies', [
            'id' => $emptyCompany->id,
        ]);
        $response->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_super_admin_cannot_delete_company_with_users()
    {
        $response = $this->actingAs($this->superAdmin)->delete(
            route('companies.destroy', $this->company->id)
        );
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
        ]);
    }

    // ===== AUTHORIZATION TESTS =====
    
    public function test_staff_cannot_edit_products()
    {
        $response = $this->actingAs($this->staff)->get(route('products.edit', $this->product->id));
        $response->assertStatus(403);
    }

    public function test_staff_cannot_delete_products()
    {
        $response = $this->actingAs($this->staff)->delete(route('products.destroy', $this->product->id));
        $response->assertStatus(403);
    }

    public function test_company_admin_cannot_access_other_company_product()
    {
        $otherCompany = Company::create([
            'name' => 'Other Company',
            'email' => 'other@test.com',
        ]);

        $otherProduct = Product::create([
            'name' => 'Other Product',
            'company_id' => $otherCompany->id,
        ]);

        $response = $this->actingAs($this->companyAdmin)->get(route('products.edit', $otherProduct->id));
        $response->assertStatus(403);
    }
}
