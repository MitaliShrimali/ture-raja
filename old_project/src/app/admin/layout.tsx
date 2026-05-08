"use client";

import React from "react";
import AdminLayout from "@/admin/layouts/AdminLayout";
import { usePathname } from "next/navigation";

export default function AdminRootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const pathname = usePathname();

  // Don't show the admin layout on the login page
  if (pathname === "/admin/login" || pathname === "/admin/login/") {
    return <>{children}</>;
  }

  return <AdminLayout>{children}</AdminLayout>;
}
