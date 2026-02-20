# Soul Renovations Website

Construction company landing page with admin panel for managing photos and reviews.

## Tech Stack
- **Frontend**: Static HTML/CSS/JS hosted on GitHub Pages
- **Backend**: Supabase (database, file storage, authentication)
- **Cost**: Free

## Files
- `index.html` - Main landing page (public)
- `admin-login.html` - Admin login page
- `admin.html` - Admin panel for managing photos and reviews
- `supabase-config.js` - Supabase project credentials
- `supabase-setup.sql` - Database setup script (run once in Supabase)
- `.github/workflows/keep-alive.yml` - Keeps Supabase free tier active

## Setup Instructions

See the detailed setup guide below to get this site live.

### Step 1: Create a Supabase Account
1. Go to **supabase.com** and click **Start your project**
2. Sign up with your GitHub account (or email)
3. Click **New Project**
4. Choose a name (e.g., `soul-renovations`), set a database password, pick a region close to you
5. Click **Create new project** and wait for it to finish setting up

### Step 2: Create the Database Tables
1. In your Supabase project, click **SQL Editor** in the left sidebar
2. Click **New query**
3. Copy the entire contents of `supabase-setup.sql` and paste it in
4. Click **Run** (or Ctrl+Enter)
5. You should see "Success. No rows returned" — this means it worked

### Step 3: Create the Photo Storage Bucket
1. Click **Storage** in the left sidebar
2. Click **New bucket**
3. Name it exactly: `photos`
4. Toggle **Public bucket** to ON
5. Click **Create bucket**
6. Click on the `photos` bucket, then click **Policies**
7. Under **Other policies under storage.objects**, click **New policy**
8. Click **For full customization**
9. Set Policy name: `Allow public read`
10. Set Allowed operation: **SELECT**
11. Leave Target roles empty (applies to all)
12. Set the policy definition to: `true`
13. Click **Review** then **Save policy**
14. Create another policy: name it `Allow auth upload`, operation **INSERT**, target role `authenticated`, definition `true`
15. Create another policy: name it `Allow auth delete`, operation **DELETE**, target role `authenticated`, definition `true`

### Step 4: Create Your Admin User
1. Click **Authentication** in the left sidebar
2. Click **Users** tab
3. Click **Add user** > **Create new user**
4. Enter your email and a strong password
5. Toggle **Auto Confirm User** to ON
6. Click **Create user**
7. Remember this email and password — you'll use it to log into the admin panel

### Step 5: Get Your Supabase Credentials
1. Click **Settings** (gear icon) in the left sidebar
2. Click **API** under Configuration
3. Copy your **Project URL** (looks like `https://abcdefg.supabase.co`)
4. Copy your **anon public** key (the long string under Project API keys)
5. Open `supabase-config.js` and replace the placeholders:
```js
const SUPABASE_URL = 'https://YOUR_PROJECT_ID.supabase.co';
const SUPABASE_ANON_KEY = 'YOUR_ANON_KEY_HERE';
```

### Step 6: Push to GitHub
1. Create a new repository on GitHub called `soul-renovations`
2. Don't add a README or .gitignore (we already have them)
3. Copy the repo URL and run:
```
git remote add origin https://github.com/YOUR_USERNAME/soul-renovations.git
git branch -M main
git push -u origin main
```

### Step 7: Enable GitHub Pages
1. Go to your GitHub repo in the browser
2. Click **Settings** tab
3. Click **Pages** in the left sidebar
4. Under **Source**, select **Deploy from a branch**
5. Select **main** branch and **/ (root)** folder
6. Click **Save**
7. Wait 1-2 minutes, then your site is live at: `https://YOUR_USERNAME.github.io/soul-renovations/`

### Step 8: Set Up Keep-Alive (prevents Supabase from pausing)
1. In your GitHub repo, click **Settings** > **Secrets and variables** > **Actions**
2. Click **New repository secret**
3. Add secret named `SUPABASE_URL` with your project URL
4. Add secret named `SUPABASE_ANON_KEY` with your anon key

### Step 9: Test Everything
1. Visit your GitHub Pages URL — the landing page should load
2. Visit `your-url/admin-login.html` — log in with the email/password from Step 4
3. Upload a test photo and add a test review
4. Go back to the landing page — they should appear in the gallery and review carousel

## Connecting a Custom Domain (Optional)
1. In your GitHub repo Settings > Pages, click **Add a custom domain**
2. Enter your domain (e.g., `soulrenovations.com`)
3. At your domain registrar, add a CNAME record pointing to `YOUR_USERNAME.github.io`
4. Wait for DNS to propagate (up to 24 hours)
5. Check **Enforce HTTPS** once it becomes available

## License
Free to use and modify for your business needs.
