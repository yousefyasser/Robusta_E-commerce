import http from "k6/http";

const appUrl = "http://host.docker.internal:8000";

export const options = {
    stages: [
        { duration: "10s", target: 5 },
        { duration: "30s", target: 10 },
        { duration: "10s", target: 0 },
    ],
};

function makeGraphQLRequest(query, variables, headers) {
    const payload = JSON.stringify({ query, variables });
    return http.post(appUrl + "/graphql", payload, { headers });
}

const getHeaders = (jwt = null) => ({
    "Content-Type": "application/json",
    ...(jwt && { Authorization: `Bearer ${jwt}` }),
});

export function setup() {
    const query = `
        mutation Register($input: CreateUserInput!) {
            registerUser(input: $input) 
        }
    `;

    const variables = {
        input: {
            name: "yousef",
            email: "yousefyasser@gmail.com",
            password: "Password123",
            password_confirmation: "Password123",
        },
    };

    const res = makeGraphQLRequest(query, variables, getHeaders());
    const jwt = JSON.parse(res.body).data.registerUser[3];

    return {
        jwt,
        graphQLUrl: appUrl + "/graphql",
        restUrl: appUrl + "/api",
    };
}

export default function (configs) {
    const headers = getHeaders(configs.jwt);

    // Verify Email
    const verifyEmailQuery = `
        mutation {
            verifyEmail
        }
    `;
    makeGraphQLRequest(verifyEmailQuery, null, headers);

    // Add Products to Cart
    for (const productId of [1, 2, 3]) {
        const addToCartQuery = `
            mutation {
                addToCart(product_id: ${productId}, quantity: 1){
                    id
                }
            }
        `;
        makeGraphQLRequest(addToCartQuery, null, headers);
    }

    // Create Address
    const createAddressQuery = `
        mutation ($addressData: CreateAddressInput!) {
            createAddress(addressData: $addressData)
        }
    `;
    const addressVariables = {
        addressData: {
            label: "a",
            recipient_name: "b",
            address_line_1: "c",
            address_line_2: "d",
            state: "e",
            city: "f",
            country: "g",
            postal_code: "h",
            phone_number: "i",
        },
    };
    const createAddressRes = makeGraphQLRequest(
        createAddressQuery,
        addressVariables,
        headers
    );

    const addressID = JSON.parse(createAddressRes.body).data.createAddress;

    // Create Order
    const checkoutQuery = `
        mutation {
            checkout(address_id: ${addressID}, payment_method_id: 1)
        }
    `;
    makeGraphQLRequest(checkoutQuery, null, headers);
}
