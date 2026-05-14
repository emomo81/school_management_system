import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { SignJWT, jwtVerify } from "jose";
import { connectDB } from "@/lib/db";
import { User } from "@/lib/models";

export type Role = "admin" | "teacher" | "student" | "parent";

export type SessionUser = {
  id: string;
  name: string;
  email: string;
  role: Role;
  profilePic?: string;
};

const cookieName = "sms_session";

function getSecret() {
  const secret = process.env.AUTH_SECRET;
  if (!secret) throw new Error("AUTH_SECRET environment variable is not set.");
  return new TextEncoder().encode(secret);
}

export async function createSession(user: SessionUser) {
  const token = await new SignJWT(user)
    .setProtectedHeader({ alg: "HS256" })
    .setIssuedAt()
    .setExpirationTime("7d")
    .sign(getSecret());

  const cookieStore = await cookies();
  cookieStore.set(cookieName, token, {
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 60 * 60 * 24 * 7,
  });
}

export async function clearSession() {
  const cookieStore = await cookies();
  cookieStore.delete(cookieName);
}

export async function getSessionUser(): Promise<SessionUser | null> {
  const cookieStore = await cookies();
  const token = cookieStore.get(cookieName)?.value;
  if (!token) return null;

  try {
    const { payload } = await jwtVerify(token, getSecret());
    return payload as SessionUser;
  } catch {
    return null;
  }
}

export async function requireUser(roles?: Role[]) {
  const session = await getSessionUser();
  if (!session) redirect("/login");
  if (roles && !roles.includes(session.role)) redirect("/dashboard");
  return session;
}

export async function refreshSessionUser(userId: string) {
  await connectDB();
  const user = await User.findById(userId).lean();
  if (!user) return null;

  const sessionUser = {
    id: user._id.toString(),
    name: user.name,
    email: user.email,
    role: user.role as Role,
    profilePic: user.profilePic,
  };
  await createSession(sessionUser);
  return sessionUser;
}
