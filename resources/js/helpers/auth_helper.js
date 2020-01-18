export function getLocalUser() {
    const userObject = localStorage.getItem("auth");
    if (!userObject) {
        return null;
    }
    const userStr = JSON.parse(userObject);
    return userStr;
}
export function setLocalUser(user) {
    let auth = JSON.stringify(user);
    localStorage.setItem("auth", auth);
}
export function removeLocalUser() {
    localStorage.removeItem("auth");
}
