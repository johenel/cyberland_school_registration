import { create } from "zustand/react";

export type User = {
    id: number,
    name: string,
    email: string
}

type AuthState = {
    user: User | null
    loading: boolean
    setUser: (user: User | null) => void
    logout: () => void
}

export const useAuthStore = create<AuthState>((set) => ({
    user: null,
    loading: true,

    setUser: (user) => set({ user, loading: false }),

    logout: () => set({ user: null }),
}))
