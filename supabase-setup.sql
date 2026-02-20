-- ===========================================
-- Soul Renovations - Supabase Database Setup
-- Run this in the Supabase SQL Editor
-- ===========================================

-- Create photos table
CREATE TABLE photos (
    id BIGSERIAL PRIMARY KEY,
    filename TEXT NOT NULL,
    alt_text TEXT,
    storage_path TEXT NOT NULL,
    public_url TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Create reviews table
CREATE TABLE reviews (
    id BIGSERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    location TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Enable Row Level Security
ALTER TABLE photos ENABLE ROW LEVEL SECURITY;
ALTER TABLE reviews ENABLE ROW LEVEL SECURITY;

-- Anyone can READ photos and reviews (public website)
CREATE POLICY "Public can read photos" ON photos FOR SELECT USING (true);
CREATE POLICY "Public can read reviews" ON reviews FOR SELECT USING (true);

-- Only logged-in admin can INSERT photos and reviews
CREATE POLICY "Admin can insert photos" ON photos FOR INSERT WITH CHECK (auth.role() = 'authenticated');
CREATE POLICY "Admin can insert reviews" ON reviews FOR INSERT WITH CHECK (auth.role() = 'authenticated');

-- Only logged-in admin can DELETE photos and reviews
CREATE POLICY "Admin can delete photos" ON photos FOR DELETE USING (auth.role() = 'authenticated');
CREATE POLICY "Admin can delete reviews" ON reviews FOR DELETE USING (auth.role() = 'authenticated');
