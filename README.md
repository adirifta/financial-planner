## Helpers

```bash
composer dump-autoload
```

## Shadcn UI

```bash
npx shadcn@latest init
```

```bash
npx shadcn@latest add
```

```
alert, alert-dialog, avatar, badge, breadcrumb, button, card, chart, checkbox, command, dialog, dropdown-menu, input, label, pagination, popover, progress, radio-group, select, separator, sheet, sonner, table, textarea, tooltip
```

## Icon di tabler

```bash
npm i @tabler/icons-react
```

## format tanggal

```bash
npm i date-fns
```

## Google font

Poppins

## Pretier untuk merapikan

```bash
npm install -D prettier prettier-plugin-organize-imports prettier-plugin-tailwindcss
```

Kemudian buat file '.prettierrc'

```bash
{
    "tabWidth": 4,
    "useTabs": false,
    "semi": true,
    "singleQuote": true,
    "trailingComma": "all",
    "bracketSpacing": true,
    "arrowParens": "always",
    "printWidth": 120,
    "endOfLine": "auto",
    "plugins": ["prettier-plugin-organize-imports", "prettier-plugin-tailwindcss"]
}
```

Kemudian buat file untuk apa aja folder yang tidak boleh dengan file '.prettierignore'

```bash
/bootstrap
/vendor
/public
/storage
```

Tambahkan di package.json pada script

```bash
"format": "prettier --write ."
```

Terakhir coba

```bash
npm run format
```

## Duster

Digunakan untuk mengetahui code yang typo

```bash
composer require tightenco/duster --dev
```

Kemudian, untuk cek yang failed nya

```bash
./vendor/bin/duster lint
```

Kemudian, untuk memperbaiki nya

```bash
./vendor/bin/duster fix
```

## Install library lodash di hooks

Digunakan untuk fungsi utilitys untuk mempermudah manipulasi data seperti array, string dll

```bash
npm install lodash
```
