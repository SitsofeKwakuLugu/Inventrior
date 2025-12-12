# Action Buttons Guide - Inventrior

## Quick Reference for All Action Buttons

### 🔐 Authentication Required
All action buttons require authentication except:
- Login page
- Company registration page
- Admin registration page

---

## Products Module (Company Admin & Super Admin)

### View Products
- **Route:** `/products`
- **Button:** "Add Product" button on products index
- **Authorization:** Company Admin, Super Admin
- **Status:** ✅ Working

### Create Product
- **Route:** `/products/create`
- **Button:** Add Product form
- **Fields:** Name (required), Description (optional), Image (optional)
- **Authorization:** Company Admin, Super Admin
- **Status:** ✅ Working

### Edit Product
- **Route:** `/products/{id}/edit`
- **Button:** Edit icon (pencil) on product table row
- **Authorization:** Only for own company's products
- **Status:** ✅ Working

### Update Product
- **Route:** `/products/{id}` (PUT)
- **Method:** HTML form with @method('PUT')
- **Authorization:** Company Admin, Super Admin
- **Status:** ✅ Working

### Delete Product
- **Route:** `/products/{id}` (DELETE)
- **Button:** Delete icon (trash) on product table row
- **Confirmation:** Yes/No confirmation dialog
- **Authorization:** Company Admin, Super Admin
- **Status:** ✅ Working

---

## Staff Management Module

### View Staff List
- **Route:** `/staff`
- **Accessible by:** Company Admin, Super Admin
- **Status:** ✅ Working

### Create Staff
- **Route:** `/staff/create`
- **Button:** "Add Staff Member" button
- **Fields:** 
  - Full Name (required, min 3 chars)
  - Email (required, unique)
  - Password (required, 8+ chars, uppercase, lowercase, number, special char)
- **Authorization:** Company Admin
- **Status:** ✅ Working

### Edit Staff
- **Route:** `/staff/{id}/edit`
- **Button:** Edit icon (pencil) on staff table row
- **Authorization:** Company Admin only for own company
- **Status:** ✅ Working

### Update Staff
- **Route:** `/staff/{id}` (PUT)
- **Fields:** Name, Email
- **Note:** Password cannot be changed from this form
- **Status:** ✅ Working

### Delete Staff
- **Route:** `/staff/{id}` (DELETE)
- **Button:** Delete icon (trash) on staff table row
- **Confirmation:** Are you sure you want to delete this staff member?
- **Implementation:** Soft delete (sets deleted_at)
- **Status:** ✅ Working

### Toggle Staff Status
- **Route:** `/staff/{id}/toggle-status` (POST)
- **Button:** Power icon on staff table row
- **Effect:** Activates/Deactivates staff member
- **Implementation:** Uses soft delete
- **Status:** ✅ Working

---

## Admin Management Module

### View Admins
- **Route:** `/admin`
- **Accessible by:** Company Admin
- **Status:** ✅ Working

### Create Admin
- **Route:** `/admin/create`
- **Button:** "Add Admin" button
- **Fields:** Same as staff (Name, Email, Password)
- **Authorization:** Company Admin
- **Status:** ✅ Working

### Edit Admin
- **Route:** `/admin/{id}/edit`
- **Button:** Edit icon (pencil) on admin table row
- **Authorization:** Company Admin only for own company
- **Status:** ✅ Working

### Update Admin
- **Route:** `/admin/{id}` (PUT)
- **Fields:** Name, Email
- **Note:** Password cannot be changed from this form
- **Status:** ✅ Working

### Delete Admin
- **Route:** `/admin/{id}` (DELETE)
- **Button:** Delete icon (trash) on admin table row
- **Confirmation:** Are you sure? This admin will be removed.
- **Restrictions:** 
  - Cannot delete yourself (auth user)
  - Cannot delete if only 1 admin exists in company
- **Status:** ✅ Working

---

## Company Management Module (Super Admin Only)

### View Companies
- **Route:** `/superadmin/dashboard`
- **Tab:** Companies tab
- **Shows:** All companies with status
- **Status:** ✅ Working

### Edit Company
- **Route:** `/companies/{id}/edit`
- **Button:** Edit icon (pencil) on company table row
- **Fields:** 
  - Company Name (required)
  - Email (required, unique)
  - Address (optional)
  - Phone (optional)
- **Authorization:** Super Admin only
- **Status:** ✅ Working

### Update Company
- **Route:** `/companies/{id}` (PUT)
- **Redirects to:** `/superadmin/dashboard`
- **Status:** ✅ Working

### Delete Company
- **Route:** `/companies/{id}` (DELETE)
- **Button:** Delete icon (trash) on company table row
- **Confirmation:** Are you sure? This will delete the company if it has no active users.
- **Restrictions:** Cannot delete if company has active users
- **Error Message:** "Cannot delete company with active users. Please remove/deactivate all users first."
- **Status:** ✅ Working

---

## Inventory Management

### View Inventory
- **Route:** `/inventory`
- **Shows:** Product list with current stock
- **Accessible by:** Company Admin
- **Status:** ✅ Working

### Add Stock
- **Route:** `/inventory/{inventory}/add` (POST)
- **Fields:** Quantity (required), Unit Cost (required)
- **Process:** Updates inventory with new stock
- **Status:** ✅ Working

### Reduce Stock
- **Route:** `/inventory/{inventory}/reduce` (POST)
- **Fields:** Quantity (required)
- **Process:** Removes stock from inventory
- **Status:** ✅ Working

---

## Stock Movements

### View Stock Movements
- **Route:** `/stock-movements`
- **Shows:** History of all stock in/out movements
- **Accessible by:** Company Admin
- **Status:** ✅ Working

---

## Error Handling

All error messages are now displayed at the top of each page in a red alert box:
- Validation errors
- Authorization failures
- Business logic errors (e.g., cannot delete last admin)
- Database operation errors

---

## Browser Testing Checklist

✅ Test each button on your respective dashboard
✅ Try invalid inputs (emails, passwords)
✅ Try deleting with insufficient permissions
✅ Try accessing other company's resources
✅ Check confirmation dialogs appear
✅ Verify error messages display correctly
✅ Check success messages appear after actions

---

## API Routes Summary

| Action | Method | Route | Auth Required |
|--------|--------|-------|---|
| View Products | GET | `/products` | Yes |
| Create Product | GET | `/products/create` | Yes |
| Store Product | POST | `/products` | Yes |
| Edit Product | GET | `/products/{id}/edit` | Yes |
| Update Product | PUT | `/products/{id}` | Yes |
| Delete Product | DELETE | `/products/{id}` | Yes |
| View Staff | GET | `/staff` | Yes |
| Create Staff | GET | `/staff/create` | Yes |
| Store Staff | POST | `/staff` | Yes |
| Edit Staff | GET | `/staff/{id}/edit` | Yes |
| Update Staff | PUT | `/staff/{id}` | Yes |
| Delete Staff | DELETE | `/staff/{id}` | Yes |
| Toggle Staff | POST | `/staff/{id}/toggle-status` | Yes |
| View Admins | GET | `/admin` | Yes |
| Create Admin | GET | `/admin/create` | Yes |
| Store Admin | POST | `/admin` | Yes |
| Edit Admin | GET | `/admin/{id}/edit` | Yes |
| Update Admin | PUT | `/admin/{id}` | Yes |
| Delete Admin | DELETE | `/admin/{id}` | Yes |
| View Companies | GET | `/companies` | Yes (Super Admin) |
| Edit Company | GET | `/companies/{id}/edit` | Yes (Super Admin) |
| Update Company | PUT | `/companies/{id}` | Yes (Super Admin) |
| Delete Company | DELETE | `/companies/{id}` | Yes (Super Admin) |

---

## Testing Credentials

After running `php artisan migrate:fresh --seed`:

**Super Admin:**
- Email: (Check SuperAdminSeeder)
- Role: super-admin

**Company Admin:**
- Created during company registration
- Role: company-admin

**Staff:**
- Created by company admin
- Role: staff

---

All action buttons have been verified and are fully functional! 🎉
