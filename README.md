# 2earn

## Template & Framework
- **Template:** Velzon
- **Framework:** Laravel 12
- **Frontend:** Livewire 3

---

## Project Overview
2earn is a comprehensive web application built with Laravel 12 and Livewire 3, utilizing the Velzon template. It offers a robust platform for managing users, financial operations, notifications, vouchers, and more, with a modular architecture and dynamic user interfaces.

---

## Main Features
- **User Management:** Registration, balances, roles, account settings
- **Financial Operations:** Coupons, shares, requests, deals, additional income
- **Notification System:** Real-time notifications for users
- **Voucher & Coupon Management:** Issue and redeem vouchers/coupons
- **Platform & Settings Configuration:** Admin and user settings
- **SMS Integration:** Send and manage SMS notifications
- **Country & Representative Management:** Regional and representative data
- **OAuth Authentication:** Secure login and API access
- **Post & Target Management:** Content and goal tracking

---

## Livewire Components
This project uses Livewire 3 for dynamic, reactive user interfaces. Key Livewire components include:
- **Balances:** Manage and display user balances
- **BusinessSectorShow, BusinessSectorIndex, BusinessSectorGroup, BusinessSectorCreateUpdate:** Business sector management and display
- **Biography:** User biography management
- **BfsToSms, BfsFunding:** BFS and SMS integration, funding operations
- **BeInfluencer:** Influencer management features
- **AdditionalIncome:** Track and manage additional income
- **Account:** User account management
- **AcceptFinancialRequest:** Handle financial request approvals
- **BussinessSectorsHome:** Business sector dashboard
- **CareerExperience:** Manage career experience data
- **BuyShares:** Share purchasing functionality
- **CDPersonality:** Personality-related features
- **CashToBfs:** Cash to BFS operations
- **Cart:** Shopping cart management
- **ConfigurationAmounts:** Configuration of financial amounts
- **ConditionCreateUpdate:** Create and update conditions

These components provide interactive features and streamline user workflows throughout the application.

---

## Setup Instructions
1. **Clone the repository and install dependencies:**
   ```bash
   composer install
   npm install
   ```
2. **Copy the example environment file and configure your settings:**
   ```bash
   cp .env.example .env
   ```
3. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```
4. **Run migrations:**
   ```bash
   php artisan migrate
   ```
5. **(Optional) Build frontend assets:**
   ```bash
   npm run build
   ```

---

## License
This project is under a private license and is the property of 2earn.cash company. Unauthorized use, distribution, or modification is strictly prohibited.
