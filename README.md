# Workplace Productivity Platform

A comprehensive workplace management system built with Next.js, TypeScript, Tailwind CSS, and Supabase.

## Modules

### Company Operations Management
Digitalize and manage company operations including companies, clients, invoices, SLAs, assets, and more.

## Tech Stack

- **Frontend**: Next.js 14+ with App Router, TypeScript, Tailwind CSS, shadcn/ui
- **Backend**: Supabase PostgreSQL, Supabase Auth, Supabase Storage, Supabase Realtime
- **Security**: Row Level Security (RLS), Role-Based Access Control (RBAC), Encrypted sensitive data

## Getting Started

```bash
npm install
npm run dev
```

## Environment Setup

Create a `.env.local` file:

```
NEXT_PUBLIC_SUPABASE_URL=your_supabase_url
NEXT_PUBLIC_SUPABASE_ANON_KEY=your_supabase_anon_key
SUPABASE_SERVICE_ROLE_KEY=your_service_role_key
```
