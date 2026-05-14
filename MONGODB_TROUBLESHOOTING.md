# MongoDB Connection Troubleshooting Guide

## The Problem
You're getting: `Error: querySrv ECONNREFUSED _mongodb._tcp.cluster0.hhx1puk.mongodb.net`

This is a DNS/Network connectivity issue preventing your machine from reaching MongoDB Atlas.

## ✅ Quick Fix Checklist

### 1. **Check MongoDB Atlas Network Access** (Most Common Issue)
This is the #1 reason for connection failures!

**Steps:**
1. Go to: https://cloud.mongodb.com/
2. Login to your account
3. Click your cluster name (`cluster0`)
4. Go to **Security** → **Network Access**
5. Look for "IP Access List"
6. **Add your IP address** or allow access from anywhere (0.0.0.0/0) for development
7. Click "Add IP Address"

### 2. **Verify Your Connection String**
Your `.env.local` should have:
```
MONGODB_URI=mongodb+srv://emmanuelmomo81_db_user:0880286157Em@cluster0.hhx1puk.mongodb.net/school_management_system
```

⚠️ Check for:
- ✓ Username: `emmanuelmomo81_db_user`
- ✓ Password: `0880286157Em` (no special chars that need encoding)
- ✓ Cluster: `cluster0.hhx1puk.mongodb.net`
- ✓ Database: `school_management_system`

### 3. **Test Internet Connectivity**
Open PowerShell/CMD and run:
```powershell
# Test basic internet
ping 8.8.8.8

# Test DNS resolution
nslookup cluster0.hhx1puk.mongodb.net

# Test MongoDB domain
nslookup _mongodb._tcp.cluster0.hhx1puk.mongodb.net
```

If `nslookup` fails, contact your ISP or check firewall settings.

### 4. **Try Alternative Connection String Format**

If the `+srv` format doesn't work, use the standard format:

1. Go to MongoDB Atlas → Your Cluster → Connect
2. Copy the connection string under "Standard Connection String"
3. Update `.env.local` with this format:
```
MONGODB_URI=mongodb+srv://emmanuelmomo81_db_user:0880286157Em@cluster0-9abc1.mongodb.net,cluster0-2def2.mongodb.net,cluster0-3ghi3.mongodb.net/school_management_system?retryWrites=true&w=majority
```

### 5. **Update Migration Timeout**

Edit `scripts/migrate-users-mongodb.ts` and increase timeout values:
```typescript
const connectionOptions = {
  serverSelectionTimeoutMS: 30000,  // Increased from 5000
  socketTimeoutMS: 60000,            // Increased from 45000
  connectTimeoutMS: 30000,           // Increased from 10000
};
```

## 🔧 Step-by-Step Fix (Recommended Order)

1. **First**: Check MongoDB Atlas Network Access (90% fix rate)
   - Add your IP address to the IP Access List
   - Wait 1-2 minutes for changes to take effect

2. **Then**: Run migration again:
   ```bash
   npm run migrate:users
   ```

3. **If still failing**: Test DNS:
   ```powershell
   nslookup cluster0.hhx1puk.mongodb.net
   ```

4. **If DNS fails**: Your ISP/firewall may be blocking MongoDB
   - Try using a VPN
   - Try from a different network (mobile hotspot)
   - Contact your network administrator

## 🆘 Advanced Troubleshooting

### Issue: "Authentication failed"
- Password might need URL encoding
- Check credentials in MongoDB Atlas Dashboard
- Go to: Security → Database Access → Edit username

### Issue: "Database doesn't exist"
- The database name in URI must match: `school_management_system`
- MongoDB creates it automatically on first insert
- Don't worry if it doesn't show in Atlas yet - it appears after data is inserted

### Issue: "Connection timeout"
- Increase timeout values in the migration script
- Or wait longer before retrying (cluster might be starting up)

## 📋 MongoDB Atlas Checklist

- [ ] Logged in to MongoDB Atlas (https://cloud.mongodb.com/)
- [ ] Found your cluster (cluster0)
- [ ] Went to Security → Network Access
- [ ] Added your IP address (or 0.0.0.0/0 for testing)
- [ ] Waited 1-2 minutes for changes to apply
- [ ] Verified connection string in `.env.local`
- [ ] Tried running migration again

## 📞 When to Seek Help

If you've done all the above steps and it still doesn't work:
1. Screenshot the Network Access page in MongoDB Atlas
2. Share your connection string (hide password)
3. Check if you can access external websites (not blocked by firewall)
4. Try from a different network if available

## 💡 Development Workaround

For now, you can:
1. Manually create users in MongoDB Atlas dashboard
2. Use the standard connection string format instead of +srv
3. Test with a cloud VM with better network connectivity

---

**Next Steps**: Check MongoDB Atlas Network Access and add your IP!
