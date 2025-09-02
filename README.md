# 🩺 Medora Backend API  
A full-featured Medical Platform API built with Laravel, implementing authentication, clinic & doctor management, pharmacy system, discounts, notifications, and appointment handling.  
The project uses Sanctum & Socialite for authentication and role-based access control for Admins, Doctors, Vets, and Patients.  

---

## 🚀 Features  

### 🔐 Authentication  
- Token-based authentication (login, register, profile).  
- Role-based access control (Admin / Doctor / Vet / Patient).  
- Secure password hashing & reset.  
- Social login with **Google Authentication**.  

### 🏥 Clinics & Doctors  
**Admin:**  
- Manage clinics (Human & Veterinary).  
- Add doctors & vets and verify accounts.  

**Patients / Pet Owners:**  
- Browse human & veterinary clinics.  
- View doctor profiles by specialty.  
- Book appointments with doctors & vets.  

### 💉 Medical & Veterinary Services  
**Human Medical Services:**  
- Wound care and dressing.  
- Blood pressure & sugar measurement.  
- Basic check-ups and follow-up services.  

**Veterinary Services:**  
- Animal vaccination.  
- Medical check-ups for pets & farm animals.  
- Wound treatment & minor surgeries.  
- On-site or home veterinary visits.  

### 📰 Blogs  
**Admin:**  
- Full CRUD for human & veterinary blogs.  

**Public:**  
- Human Blog: health awareness, nutrition, chronic illnesses, first aid, etc.  
- Veterinary Blog: animal nutrition, vaccination schedules, common diseases, care tips.  

### 💊 Pharmacy  
**Admin:**  
- Manage human & veterinary pharmacy categories & products.  

**Users:**  
- Browse products (Human & Veterinary sections).  
- Add to cart & manage cart.  
- Secure checkout with integrated payments.  

### 💳 Payment Integration  
- Multiple online payment methods.  
- Secure and encrypted transactions.  
- Integrated with checkout for pharmacy purchases.  
- Linked with discounts & offers system.  

### 🎁 Discounts & Offers  
**Admin:**  
- Create and manage discounts.  
- Apply discounts automatically at checkout.  

**Users:**  
- Receive email notifications when new discounts are available.  

### 📦 Appointments & Chat  
**Patients / Pet Owners:**  
- Book appointments with doctors & vets.  
- Secure in-app chat with booked doctors.  

**System:**  
- Rule-based chat for FAQs (appointments, availability, clinic info).  

### 📢 Notifications  
- In-app and email notifications for:  
  - Appointment booking, updates, and cancellations.  
  - New messages from doctors.  
  - New discounts and offers.  

### 🔍 Smart Search  
- Keyword-based search for:  
  - Doctors.  
  - Clinics.  
  - Services.  
  - Pharmacies.  
  - Blog articles.  
