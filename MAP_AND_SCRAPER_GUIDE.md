# 📍 Map & Scraper System - Complete Setup Guide

## ✅ **WHAT'S BEEN FIXED & ADDED**

### 1. 🗺️ **MAP SYSTEM - NOW FULLY FUNCTIONAL**

#### **Features Implemented:**
- ✅ **User Location Tracking** - Shows user's current location with blue marker
- ✅ **Nearby Events Detection** - Displays all events within 50km radius
- ✅ **Color-Coded Pins:**
  - 🟢 **Green** = Running events
  - 🟡 **Yellow** = Hiking events
  - 🔵 **Blue** = Cycling events
  - 🔵 **Blue** = User's location
- ✅ **Distance Calculation** - Shows distance from user to each event (Haversine formula)
- ✅ **Info Panel** - Top-right displays user location and nearby event count
- ✅ **Click-to-View** - Click any marker to see event details in popup
- ✅ **Center Button** - Button to quickly recenter map on user location
- ✅ **Error Handling** - Graceful fallback if location denied

#### **How It Works:**
1. User visits map page
2. Browser requests location permission
3. User's location shows as blue marker
4. All nearby events display with color-coded pins
5. Distance calculated and shown in info windows
6. User can click any pin to see full event details

#### **Try It Now:**
- Visit: `http://localhost:8000/user/events/map`
- Allow location access when prompted
- Click any event pin to see details

---

### 2. 🕷️ **SCRAPER SYSTEM - FULLY FUNCTIONAL WITH SCHEDULER**

#### **Scrapers Available:**

| Platform | Status | Command | Notes |
|----------|--------|---------|-------|
| **Eventbrite** | ✅ Active | `php artisan events:scrape eventbrite` | Popular event platform |
| **Ticket2U** | ✅ Active | `php artisan events:scrape ticket2u` | Malaysian ticketing |
| **JomRun** | ✅ Active | `php artisan events:scrape jomrun` | Running events focus |
| **SGTrek** | ✅ Active | `php artisan events:scrape sgtrek` | Hiking/trekking events |
| **CheckpointSpot** | ✅ Active | `php artisan events:scrape checkpointspot` | Running/cycling races |
| **Finishers** | ✅ Active | `php artisan events:scrape finishers` | Running events |
| **Meetup** | ✅ Active | `php artisan events:scrape meetup` | Community events |

#### **Scheduler Configuration:**

**Current Schedule:**
```
⏰ Daily: 3:00 AM - Run all scrapers
📅 Weekly: Sundays 4:00 AM - Cleanup completed events
🔄 Twice Daily: 2:00 AM & 2:00 PM - Cleanup invalid events
```

#### **Manual Scraping Commands:**

```bash
# Scrape all platforms
php artisan events:scrape

# Scrape specific platform
php artisan events:scrape eventbrite
php artisan events:scrape ticket2u
php artisan events:scrape jomrun
php artisan events:scrape sgtrek
php artisan events:scrape checkpointspot
php artisan events:scrape finishers
php artisan events:scrape meetup

# Check available commands
php artisan list
```

#### **What Gets Scraped:**
- Event name
- Description
- Date and time
- Location (address)
- Latitude/Longitude (geocoded)
- Category (Running/Hiking/Cycling - auto-detected)
- Image (if available)
- Source URL
- Source platform

#### **How Scheduler Works:**
1. Cron job runs at configured time
2. Scheduler picks up `events:scrape` command
3. All platforms scraped automatically
4. Events inserted or updated in database
5. Log entry created with statistics
6. Invalid/completed events cleaned up

#### **Enable Scheduler (Production):**
Add to crontab:
```bash
* * * * * cd /path/to/outdoor-events && php artisan schedule:run >> /dev/null 2>&1
```

This runs every minute and executes scheduled tasks.

#### **How to Test Scheduler Locally:**
```bash
# Test the schedule without running (shows what will run)
php artisan schedule:test

# Manually trigger the schedule
php artisan schedule:run

# Check scheduled commands
php artisan schedule:list
```

---

## 🧪 **TESTING THE FEATURES**

### **Test Map Functionality:**

1. **Open Map Page:**
   - Navigate to: `http://localhost:8000/user/events/map`
   - Browser will request location permission
   - Click "Allow"

2. **Expected Results:**
   - ✅ Map loads with your location
   - ✅ Blue marker shows your position
   - ✅ Colored pins show nearby events
   - ✅ Info panel shows nearby count
   - ✅ Clicking pins shows event details with distance

3. **Test Center Button:**
   - Click "Center on Me" button
   - Map should recenter on your location

### **Test Scraper Commands:**

1. **Test Single Platform:**
   ```bash
   php artisan events:scrape eventbrite
   ```
   Expected output:
   ```
   Starting scraper...
   Scraping Eventbrite...
   Eventbrite: Created X, Updated Y
   ```

2. **Test All Platforms:**
   ```bash
   php artisan events:scrape
   ```
   Expected output: Stats for all 7 platforms

3. **Check Log File:**
   ```bash
   Get-Content storage/logs/laravel.log -Tail 20
   ```
   Should show scraper execution details

---

## 📊 **CURRENT SYSTEM STATUS**

### **Map System:**
- ✅ User geolocation enabled
- ✅ Event markers with color coding
- ✅ Distance calculation functional
- ✅ Click-to-view popups working
- ✅ Error handling in place
- ✅ Responsive design

### **Scraper System:**
- ✅ All 7 platforms configured
- ✅ Automatic event categorization
- ✅ Geolocation enabled
- ✅ Daily scheduler configured
- ✅ Cleanup routines scheduled
- ✅ Error handling and logging
- ✅ Duplicate detection (updateOrCreate)

### **Data Flow:**
1. Scraper runs → Fetches events
2. Event filter → Categorizes by activity
3. Geocoding → Gets lat/lng
4. Image download → Stores locally
5. Database → Inserts/updates
6. User → Views on map/list
7. Scheduler → Runs daily automatically

---

## 🚀 **FOR PRODUCTION DEPLOYMENT**

### **Step 1: Configure Cron Job**
Add to server crontab:
```bash
* * * * * cd /var/www/outdoor-events && php artisan schedule:run >> /dev/null 2>&1
```

### **Step 2: Test Scheduler**
```bash
# SSH into your server
ssh user@yourdomain.com

# Test the schedule
php artisan schedule:test

# Run manually to verify
php artisan schedule:run
```

### **Step 3: Monitor Logs**
```bash
# Check scraper logs
tail -f storage/logs/laravel.log | grep scrape

# Or via dashboard
php artisan logs:tail
```

### **Step 4: Adjust Schedule if Needed**
Edit `app/Console/Kernel.php` to change:
- Scrape time (currently 3 AM)
- Cleanup frequency
- Add more platforms if needed

---

## 📱 **USER EXPERIENCE FLOW**

### **Map View (New Feature):**
1. User clicks "Map" button
2. Grants location permission
3. Map shows their location
4. Nearby events display with pins
5. Can click pins to see details
6. Can view full event page

### **Event List View (Enhanced):**
1. User sees all events with filters
2. Can filter by category (Running/Hiking/Cycling)
3. Can search by name/location
4. Can view on map from list view

### **Integration Benefits:**
- Users find events near them
- Scraped events populate database
- No manual event entry needed
- Automatic daily updates
- Better user engagement

---

## 🔍 **TROUBLESHOOTING**

### **Map Not Loading:**
```bash
# Check API key
php artisan tinker
>>> config('services.google_maps.api_key')
>>> // Should return the API key

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### **Location Not Showing:**
- Check browser console for errors
- Ensure HTTPS or localhost
- Check browser location permissions
- Try different browser

### **Scraper Not Running:**
```bash
# Test command
php artisan events:scrape

# Check if cron configured
crontab -l | grep schedule

# View scheduled tasks
php artisan schedule:list

# Check logs
tail -f storage/logs/laravel.log
```

### **No Events On Map:**
- Events must have valid latitude/longitude
- Run scraper: `php artisan events:scrape`
- Check database: `php artisan tinker` → `App\Models\Event::where('latitude', '!=', null)->count()`

---

## 📈 **NEXT IMPROVEMENTS (Optional)**

1. **Add Event Filtering on Map:**
   - Show only Running/Hiking/Cycling
   - Search box on map

2. **Event Recommendations:**
   - Based on user history
   - Similar events suggestion

3. **Event Notifications:**
   - Email when new events near user
   - Reminders before event

4. **Rate Limiting on Scraper:**
   - Prevent API blocking
   - Stagger requests

5. **Scraper Admin Dashboard:**
   - View scraper logs
   - Manual trigger option
   - Status monitoring

---

**System Status: ✅ PRODUCTION READY**

All scrapers working. Scheduler configured. Map fully functional with geolocation support.

---
*Last Updated: February 1, 2026*
