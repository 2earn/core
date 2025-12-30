# Project Development Guidelines

## ⚠️ IMPORTANT: Build Commands

### 🚫 NEVER EXECUTE
```bash
npm run build
npm run production
npm run prod
yarn build
yarn production
```

### ❌ Why Not?
- Build process can be time-consuming
- May trigger unnecessary asset compilation
- Could overwrite existing compiled assets
- Should only be done in controlled deployment environment
- May cause conflicts with existing build artifacts

---

## ✅ Safe Development Commands

### Development Mode (OK to use)
```bash
npm run dev
npm run watch
npm run hot
```

### Other Safe Commands
```bash
npm install          # Install dependencies
npm run lint         # Lint code
npm run test         # Run tests
composer install     # Install PHP dependencies
php artisan migrate  # Run migrations
```

---

## 📝 Deployment Process

When ready for production build:

1. **Commit all changes** to version control
2. **Test thoroughly** in development
3. **Create pull request** for review
4. **Let CI/CD pipeline** handle the build
5. **Or build manually** in controlled environment

### Manual Build (Only when necessary)
```bash
# Only in production environment
npm run production

# Or with specific configuration
NODE_ENV=production npm run build
```

---

## 🔧 Development Workflow

### CSS/JS Changes
1. Make changes to source files
2. Let Vite hot reload handle updates
3. Test in browser
4. Commit source files only

### Don't Commit
- `public/build/*` (build artifacts)
- `node_modules/*`
- Compiled assets

---

## 📌 Remember

**Source files are edited, not build output!**

- Edit: `resources/css/*.css`
- Edit: `resources/js/*.js`
- Don't edit: `public/build/*`

The build process is for **production deployment only**.

---

*Last Updated: December 30, 2025*
*Project: 2Earn.cash*
*Status: Active Development*

