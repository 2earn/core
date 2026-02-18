# Services API Coverage Report

> Generated: 2026-02-18  
> Total services: **107** | Used in controllers: **59** | **Not exposed via API: 48**

---

## ❌ Services NOT Exposed as API Endpoints

### Namespaced / Subfolder Services

| Service Class | Path |
|---|---|
| `Carts` | `Carts\Carts` |
| `CartsFacade` | `Carts\CartsFacade` |
| `CommittedInvestorRequestService` | `CommittedInvestor\CommittedInvestorRequestService` |
| `CommunicationFacade` | `Communication\CommunicationFacade` |
| `DealChangeRequestService` | `DealChangeRequest\DealChangeRequestService` |
| `InstructorRequestService` | `InstructorRequest\InstructorRequestService` |
| `OrderingFacade` | `Orders\OrderingFacade` |
| `OrderingSimulation` | `Orders\OrderingSimulation` |
| `OrderingSimulationFacade` | `Orders\OrderingSimulationFacade` |
| `Sponsorship` | `Sponsorship\Sponsorship` |
| `SurveyQuestionChoiceService` | `Survey\SurveyQuestionChoiceService` |
| `SurveyQuestionService` | `Survey\SurveyQuestionService` |
| `SurveyResponseItemService` | `Survey\SurveyResponseItemService` |
| `SurveyResponseService` | `Survey\SurveyResponseService` |
| `SurveyService` | `Survey\SurveyService` |
| `ConditionService` | `Targeting\ConditionService` |
| `GroupService` | `Targeting\GroupService` |
| `TargetingFacade` | `Targeting\TargetingFacade` |
| `TargetService` | `Targeting\TargetService` |
| `UserToken` | `Users\UserToken` |
| `UserTokenFacade` | `Users\UserTokenFacade` |

### Root-level Services

| Service Class | File | Notes |
|---|---|---|
| `ActionHistorysService` | `ActionHistorysService.php` | |
| `AmountService` | `AmountService.php` | |
| `BalanceOperationService` | `BalanceOperationService.php` | Duplicate of `Balances\BalanceOperationService` |
| `CartService` | `CartService.php` | |
| `CashService` | `CashService.php` | |
| `CommandeServiceManager` | `CommandeServiceManager.php` | |
| `CommentService` | `CommentService.php` | Duplicate of `Comments\CommentsService` |
| `ContactUserService` | `ContactUserService.php` | |
| `CountryService` | `CountryService.php` | cf. `CountriesService` which IS used |
| `IdentificationUserRequestService` | `IdentificationUserRequestService.php` | |
| `InstructorRequestService` | `InstructorRequestService.php` | Root-level duplicate |
| `MessageService` | `MessageService.php` | |
| `MettaUsersService` | `MettaUsersService.php` | |
| `NotificationService` | `NotificationService.php` | |
| `NotifyEarn` | `NotifyEarn.php` | |
| `NotifyHelper` | `NotifyHelper.php` | |
| `OperationCategoryService` | `OperationCategoryService.php` | |
| `OrderDetailService` | `OrderDetailService.php` | |
| `SharesService` | `SharesService.php` | |
| `SmsHelper` | `SmsHelper.php` | |
| `TransactionManager` | `TransactionManager.php` | |
| `TranslaleModelService` | `TranslaleModelService.php` | Duplicate of `Translation\TranslaleModelService` |
| `UserContactNumberService` | `UserContactNumberService.php` | |
| `UserContactService` | `UserContactService.php` | |
| `UserNotificationSettingService` | `UserNotificationSettingService.php` | |
| `UserNotificationSettingsService` | `UserNotificationSettingsService.php` | |

---

## ✅ Services WITH API Coverage (59)

| Service | API Area |
|---|---|
| `Balances\BalanceOperationService` | `/v1/balance/operations`, `/v2/balance/operations` |
| `Balances\Balances` + `BalancesFacade` | `/v1/add-cash`, `/v1/api/transfert` |
| `Balances\BalanceService` | `/v1/user-balances`, `/v1/shares-*` |
| `Balances\BalanceTreeService` | `/v1/user/tree` |
| `Balances\CashBalancesService` | `/v1/api/user/cash`, `/mobile/cash-balance` |
| `Balances\ShareBalanceService` | `/v1/api/shares/*` |
| `Balances\OperationCategoryService` | `/v2/operation-categories` |
| `BalancesManager` | `/v1/add-cash`, `/v1/update-balance-*` |
| `BusinessSector\BusinessSectorService` | `/v2/business-sectors` |
| `Comments\CommentsService` | `/v2/comments` |
| `Commission\PlanLabelService` | `/partner/plan-label` |
| `CommissionBreakDownService` | `/v2/commission-breakdowns` |
| `Communication\Communication` | `/v2/communication` |
| `CommunicationBoardService` | `/v2/communication-board` |
| `CountriesService` | `/v1/countries` |
| `Coupon\BalanceInjectorCouponService` | `/v2/balance-injector-coupons` |
| `Coupon\CouponService` | `/v1/coupons`, `/v2/coupons` |
| `Dashboard\SalesDashboardService` | `/partner/sales/dashboard` |
| `Deals\DealProductChangeService` | `/v2/deal-product-changes` |
| `Deals\DealService` | `/v2/deals`, `/partner/deals` |
| `Deals\PendingDealChangeRequestsInlineService` | `/v2/pending-deal-change-requests` |
| `Deals\PendingDealValidationRequestsInlineService` | `/v2/pending-deal-validation-requests` |
| `EntityRole\EntityRoleService` | `/v2/entity-roles` |
| `EventService` | `/v2/events` |
| `Faq\FaqService` | `/v2/faqs` |
| `FinancialRequest\FinancialRequestService` | `/v1/request` |
| `Hashtag\HashtagService` | `/v2/hashtags` |
| `IdentificationRequestService` | `ApiController` (sankey/payment) |
| `Items\ItemService` | `/v2/items`, `/partner/items` |
| `News\NewsService` | `/v2/news` |
| `Orders\Ordering` | `/partner/orders` |
| `Orders\OrderService` | `/v2/orders` |
| `Partner\PartnerService` | `/v2/partners` |
| `PartnerPayment\PartnerPaymentService` | `/v2/partner-payments`, `/partner/payments` |
| `PartnerRequest\PartnerRequestService` | `/partner/partner-requests` |
| `Platform\AssignPlatformRoleService` | `/v2/assign-platform-roles` |
| `Platform\PendingPlatformChangeRequestsInlineService` | `/v2/pending-platform-change*` |
| `Platform\PendingPlatformRoleAssignmentsInlineService` | `/v2/pending-platform-role-assignments*` |
| `Platform\PlatformChangeRequestService` | `/v2/platform-change-requests` |
| `Platform\PlatformService` | `/v2/platforms`, `/partner/platforms` |
| `Platform\PlatformTypeChangeRequestService` | `/v2/platform-type-change-requests` |
| `Platform\PlatformValidationRequestService` | `/v2/platform-validation-requests` |
| `RepresentativesService` | `/v1/representatives` |
| `Role\RoleService` | `/v2/roles` |
| `Settings\SettingService` | `/v1/settings`, `/v1/amounts` |
| `settingsManager` | Various v1 controllers |
| `SimulationService` | `/order/simulate`, `/order/run-simulation` |
| `sms\SmsService` | `/v1/send-sms` |
| `Sponsorship\SponsorshipFacade` | `ApiController` |
| `Targeting\Targeting` | `/v1/target/{id}/data` |
| `Translation\TranslaleModelService` | `/v2/translale-models` |
| `Translation\TranslateTabsService` | `/v2/translate-tabs` |
| `Translation\TranslationMergeService` | `/v2/translation-merge` |
| `UserBalances\UserBalancesHelper` | `/v2/user-balances` |
| `UserBalances\UserCurrentBalanceHorisontalService` | `/v2/user-balances/horizontal` |
| `UserBalances\UserCurrentBalanceVerticalService` | `/v2/user-balances/vertical` |
| `UserGuide\UserGuideService` | `/v2/user-guides` |
| `UserService` | `ApiController` |
| `VipService` | `/v1/vip` |

---

## 📌 Key Observations

1. **Survey module entirely unexposed** — all 5 `Survey\*` services have no API endpoints.
2. **Targeting module mostly unexposed** — only `Targeting` itself is used; `ConditionService`, `GroupService`, `TargetingFacade`, `TargetService` are not.
3. **Cart system not exposed** — `Carts\Carts`, `Carts\CartsFacade`, `CartService` have no API endpoints despite the cart-based order flow.
4. **Notification/messaging internal only** — `NotificationService`, `MessageService`, `NotifyEarn`, `NotifyHelper` are used internally with no API exposure.
5. **Several root-level services are legacy duplicates** of their namespaced counterparts and should likely be cleaned up.
6. **`DealChangeRequestService`** has no direct endpoint — deal change requests flow through `DealService` instead.
